<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncPortfolioImages extends Command
{
    protected $signature = 'portfolio:sync-images
        {--source=https://inoweb.my.id : Basis URL situs sumber}
        {--dry-run : Ambil halaman dan hitung gambar tanpa mengunduh/mengubah apa pun}
        {--slug= : Sinkron satu project saja berdasarkan slug}
        {--insecure : Lewati verifikasi sertifikat SSL (bila CA server tidak lengkap)}';

    protected $description = 'Tarik gambar cover + galeri portfolio dari situs sumber (halaman detail)';

    public function handle(): int
    {
        $source = rtrim((string) $this->option('source'), '/');
        $dryRun = (bool) $this->option('dry-run');
        $onlySlug = $this->option('slug') ? (string) $this->option('slug') : null;

        $projects = Project::query()->orderBy('id');
        if ($onlySlug) {
            $projects->where('slug', $onlySlug);
        }
        $projects = $projects->get();
        if ($projects->isEmpty()) {
            $this->warn('Tidak ada project yang cocok.');
            return self::SUCCESS;
        }

        $ok = 0;
        $skipped = 0;
        foreach ($projects as $project) {
            if ($this->syncProject($project, $source, $dryRun)) {
                $ok++;
            } else {
                $skipped++;
            }
        }
        $this->info($dryRun
            ? "Dry-run selesai: {$ok} project terpetakan, {$skipped} dilewati."
            : "Selesai: {$ok} project tersinkron, {$skipped} dilewati.");

        return self::SUCCESS;
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout(60)->retry(2, 500);
        if ($this->option('insecure')) {
            $client->withOptions(['verify' => false]);
        }

        return $client;
    }

    private function syncProject(Project $project, string $source, bool $dryRun): bool
    {
        $pageUrl = $source.'/portfolio/'.$project->slug;
        try {
            $page = $this->http()->get($pageUrl);
        } catch (\Throwable $e) {
            $this->warn("[{$project->slug}] halaman tak terjangkau: {$e->getMessage()}");
            return false;
        }
        if (! $page->successful()) {
            $this->warn("[{$project->slug}] halaman HTTP {$page->status()}, dilewati.");
            return false;
        }

        $images = $this->extractImages($page->body(), $source);
        if (empty($images)) {
            $this->warn("[{$project->slug}] pola gambar tidak ditemukan, dilewati.");
            return false;
        }

        if ($dryRun) {
            $this->line("[{$project->slug}] ".count($images).' gambar (1 cover + '.max(0, count($images) - 1).' galeri).');
            return true;
        }

        // Unduh + konversi SEMUA dulu ke nama staging (tak pernah sama dengan
        // data lama); database disentuh hanya bila sukses penuh.
        $disk = Storage::disk('public');
        $staged = [];
        foreach (array_values($images) as $i => $url) {
            try {
                $res = $this->http()->get($url);
            } catch (\Throwable $e) {
                $this->warn("[{$project->slug}] gagal unduh {$url}: {$e->getMessage()}");
                $this->rollbackStaged($staged);
                return false;
            }
            if (! $res->successful() || $res->body() === '') {
                $this->warn("[{$project->slug}] unduhan HTTP {$res->status()}: {$url}");
                $this->rollbackStaged($staged);
                return false;
            }
            $info = @getimagesizefromstring($res->body());
            $mime = $info['mime'] ?? '';
            if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
                $this->warn("[{$project->slug}] bukan gambar valid: {$url}");
                $this->rollbackStaged($staged);
                return false;
            }

            $ext = $mime === 'image/jpeg' ? 'jpg' : ($mime === 'image/png' ? 'png' : 'webp');
            $tmpRel = 'projects-sync/sync-tmp-'.$project->slug.'-'.$i.'.'.$ext;
            $disk->put($tmpRel, $res->body());

            // Nama staging unik per run agar tak menimpa file lama sebelum swap.
            $stagedRel = 'projects-sync/sync-new-'.$project->slug.'-'.$i.'.webp';
            if ($ext === 'webp') {
                if ($disk->exists($stagedRel)) {
                    $disk->delete($stagedRel);
                }
                $disk->move($tmpRel, $stagedRel);
            } else {
                $converted = convertStoredImageToWebp($tmpRel, 80);
                if ($converted === null) {
                    $this->warn("[{$project->slug}] konversi WebP gagal: {$url}");
                    $disk->delete($tmpRel);
                    $this->rollbackStaged($staged);
                    return false;
                }
                if ($stagedRel !== $converted) {
                    if ($disk->exists($stagedRel)) {
                        $disk->delete($stagedRel);
                    }
                    $disk->move($converted, $stagedRel);
                }
                $disk->delete($tmpRel);
            }
            $staged[] = ['role' => $i === 0 ? 'cover' : 'gallery', 'staged' => $stagedRel, 'order' => $i];
        }

        // Swap atomik: hapus gambar lama (file + baris), pindahkan staging
        // ke nama final, lalu daftarkan baris baru.
        foreach ($project->images as $old) {
            $oldRel = ltrim((string) $old->image, '/');
            if (str_starts_with($oldRel, 'storage/')) {
                $disk->delete(substr($oldRel, strlen('storage/')));
            }
            $old->delete();
        }
        foreach ($staged as $row) {
            $finalRel = 'projects-sync/'.$project->slug.($row['order'] === 0 ? '' : '-galeri-'.str_pad((string) $row['order'], 2, '0', STR_PAD_LEFT)).'.webp';
            if ($disk->exists($finalRel)) {
                $disk->delete($finalRel);
            }
            $disk->move($row['staged'], $finalRel);
            $project->images()->create([
                'image' => 'storage/'.$finalRel,
                'type' => $row['role'],
                'sort_order' => $row['order'],
            ]);
        }
        forgetSettingsCache();

        $this->line("[{$project->slug}] OK: 1 cover + ".(count($staged) - 1).' galeri.');
        return true;
    }

    /**
     * Ekstrak daftar URL gambar dari blok `images: JSON.parse('...')`
     * pada halaman detail sumber. Hanya URL se-host sumber yang diambil.
     *
     * Catatan: isi blok BUKAN JSON mentah, melainkan literal string JS
     * (tanda kutip di-escape sebagai \u0022) sehingga perlu dua tahap
     * decoding: literal JS dulu, baru JSON.
     *
     * @return string[]
     */
    private function extractImages(string $html, string $source): array
    {
        if (! preg_match("/images:\\s*JSON\\.parse\\('(.*?)'\\)/s", $html, $m)) {
            return [];
        }
        // Tahap 1: tiru decoding literal string JS.
        $json = json_decode('"'.$m[1].'"');
        if (! is_string($json)) {
            // Fallback: konversi manual hanya untuk \uXXXX.
            $json = preg_replace_callback(
                '/\\\\u([0-9a-fA-F]{4})/',
                fn ($mm) => mb_chr((int) hexdec($mm[1]), 'UTF-8'),
                $m[1]
            );
        }
        // Tahap 2: decode JSON yang sebenarnya.
        $decoded = is_string($json) ? json_decode($json, true) : null;
        if (! is_array($decoded)) {
            return [];
        }
        $urls = [];
        foreach ($decoded as $item) {
            $src = is_array($item) ? ($item['src'] ?? '') : '';
            if (! is_string($src) || $src === '') {
                continue;
            }
            if (! str_starts_with($src, $source.'/')) {
                continue;
            }
            if (in_array($src, $urls, true)) {
                continue;
            }
            $urls[] = $src;
        }

        return array_values($urls);
    }

    /** Hapus file staging bila sebuah project gagal di tengah jalan. */
    private function rollbackStaged(array $staged): void
    {
        $disk = Storage::disk('public');
        foreach ($staged as $row) {
            $disk->delete((string) ($row['staged'] ?? ''));
        }
        foreach ($disk->files('projects-sync') as $leftover) {
            if (str_contains($leftover, 'sync-tmp-') || str_contains($leftover, 'sync-new-')) {
                $disk->delete($leftover);
            }
        }
    }
}
