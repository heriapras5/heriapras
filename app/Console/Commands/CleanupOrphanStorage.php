<?php

namespace App\Console\Commands;

use App\Models\ProjectImage;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanStorage extends Command
{
    protected $signature = 'storage:cleanup-orphans
        {--force : Hapus file yatim (default: dry-run, hanya laporan)}
        {--older-than=24 : Hanya sentuh file lebih tua dari N jam (pengaman upload berjalan)}';

    protected $description = 'Hapus file yatim di storage publik yang tidak dirujuk database';

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $olderThanHours = max(0, (int) $this->option('older-than'));
        $cutoff = now()->subHours($olderThanHours)->getTimestamp();
        $disk = Storage::disk('public');

        // Kumpulkan semua path file yang masih dirujuk database.
        $referenced = [];
        foreach (ProjectImage::query()->pluck('image') as $path) {
            $referenced[$this->diskPath((string) $path)] = true;
        }
        foreach (['profile_photo', 'logo_image', 'favicon', 'seo_og_image', 'cv_url'] as $key) {
            $value = (string) Setting::where('key', $key)->value('value');
            if ($value !== '') {
                $referenced[$this->diskPath($value)] = true;
            }
        }

        $deleted = 0;
        $kept = 0;
        $dangling = [];

        foreach ($disk->allFiles() as $file) {
            if (basename($file) === '.gitignore') {
                continue;
            }
            if (isset($referenced[$file])) {
                $kept++;
                continue;
            }
            if ($disk->lastModified($file) >= $cutoff) {
                $kept++;
                continue;
            }
            if ($force) {
                $disk->delete($file);
                $this->line("Dihapus: storage/{$file}");
            } else {
                $this->line("Yatim: storage/{$file}");
            }
            $deleted++;
        }

        // Arah sebaliknya: referensi DB yang file-nya hilang hanya dilaporkan,
        // tidak pernah diubah otomatis.
        foreach (array_keys($referenced) as $file) {
            if ($file === '' || $disk->exists($file)) {
                continue;
            }
            $dangling[] = 'storage/'.$file;
        }
        foreach ($dangling as $missing) {
            $this->warn("Referensi tanpa file: {$missing}");
        }

        forgetSettingsCache();

        if ($force) {
            $this->info("Selesai: {$deleted} file yatim dihapus, {$kept} dipertahankan, ".count($dangling).' referensi hilang.');
        } else {
            $this->info("Dry-run: {$deleted} file yatim akan dihapus, {$kept} dipertahankan, ".count($dangling).' referensi hilang. Tambahkan --force untuk eksekusi.');
        }

        return self::SUCCESS;
    }

    private function diskPath(string $value): string
    {
        $rel = ltrim($value, '/');
        if (str_starts_with($rel, 'storage/')) {
            return substr($rel, strlen('storage/'));
        }

        return $rel;
    }
}
