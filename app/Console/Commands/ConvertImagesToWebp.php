<?php

namespace App\Console\Commands;

use App\Models\ProjectImage;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-to-webp
        {--dry-run : Tampilkan rencana konversi tanpa mengubah file maupun database}
        {--quality=80 : Kualitas WebP 1-100}';

    protected $description = 'Konversi gambar JPEG/PNG lama (project + pengaturan) menjadi WebP untuk hemat storage';

    public function handle(): int
    {
        $quality = max(1, min(100, (int) $this->option('quality')));
        $dryRun = (bool) $this->option('dry-run');
        $disk = Storage::disk('public');

        $targets = [];
        foreach (ProjectImage::query()->get(['id', 'image']) as $img) {
            $targets[] = ['kind' => 'project_image', 'id' => $img->id, 'path' => (string) $img->image];
        }
        foreach (['profile_photo', 'logo_image', 'favicon', 'seo_og_image'] as $key) {
            $value = (string) Setting::where('key', $key)->value('value');
            if ($value !== '') {
                $targets[] = ['kind' => 'setting', 'id' => $key, 'path' => $value];
            }
        }

        $converted = 0;
        $skipped = 0;
        $savedBytes = 0;

        foreach ($targets as $target) {
            $rel = ltrim($target['path'], '/');
            if (! str_starts_with($rel, 'storage/')) {
                $skipped++;
                continue;
            }
            $diskPath = substr($rel, strlen('storage/'));
            if (! $disk->exists($diskPath)) {
                $this->warn("Lewati (file hilang): {$target['path']}");
                $skipped++;
                continue;
            }
            $ext = strtolower(pathinfo($diskPath, PATHINFO_EXTENSION));
            if (in_array($ext, ['webp', 'ico'], true)) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $size = $disk->size($diskPath);
                $this->line("Akan dikonversi [{$target['kind']}:{$target['id']}]: {$target['path']} (".$this->formatBytes($size).')');
                $converted++;
                continue;
            }

            $oldSize = $disk->size($diskPath);
            $newDiskPath = convertStoredImageToWebp($diskPath, $quality);
            if ($newDiskPath === null) {
                $this->warn("Lewati (bukan JPEG/PNG atau gagal): {$target['path']}");
                $skipped++;
                continue;
            }
            $savedBytes += max(0, $oldSize - $disk->size($newDiskPath));
            $disk->delete($diskPath);
            $newValue = 'storage/'.$newDiskPath;
            if ($target['kind'] === 'setting') {
                Setting::where('key', $target['id'])->update(['value' => $newValue]);
            } else {
                ProjectImage::where('id', $target['id'])->update(['image' => $newValue]);
            }
            $converted++;
            $this->line("OK: {$target['path']} -> {$newValue}");
        }

        forgetSettingsCache();

        if ($dryRun) {
            $this->info("Dry-run selesai: {$converted} file akan dikonversi, {$skipped} dilewati.");
        } else {
            $this->info("Selesai: {$converted} dikonversi (hemat ".$this->formatBytes($savedBytes)."), {$skipped} dilewati.");
        }

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 2).' MB';
    }
}
