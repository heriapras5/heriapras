<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Seeder;

/**
 * Sinkron gambar cover portofolio dari situs produksi (inoweb.my.id).
 * File sumber diletakkan di database/seeders/data/portfolio/*.png|jpg,
 * lalu disalin ke storage/app/public/projects-sync/ dan didaftarkan
 * sebagai cover image masing-masing project (per slug).
 */
class PortfolioImageSyncSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'website-duyufuna' => '4gimxWUaflexs1iAlosE4bi5GIQZDAXkBKMTlq1V.png',
            'website-rdr-interior-aluminium' => 'xQzzWNWp9E6tMAq0tjgu2W0ywnsMwMf0MSK4ZXUf.png',
            'website-horeca-furniture-2' => 'kTHup4JBrG3xLuDVQXDjiPUK7zgKPEgX5X5enJYc.jpg',
            'website-outdoor-furniture-innovateak-2' => 'FjRNWy28jMLzs7P8Ipz623Dt2il67gzP8ZaVMmnJ.png',
            'website-bukit-jati' => 'wmpfLCU7Dy6T8LIrDkQbh7Sp3rsboK5sFSiA2F5c.png',
            'website-mury-furniture' => 'v0fiZh9uPYQAKjxffHWCnSKX4L4AB0x5J8GjEzEy.png',
            'smopf-sistem-manajemen-order-produksi-furniture' => 'ZfVEV7L5FtoGv3MfVd9iIo7gKyKzEz4MI5y53DZA.png',
            'aplikasi-manajemen-service-hp-juneo-phone' => 'kHqKhgB1G9FHD4hLo3quayrPTCalq8meJ9xt8YWm.png',
        ];

        $srcDir = database_path('seeders/data/portfolio');
        $destDir = storage_path('app/public/projects-sync');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        foreach ($map as $slug => $file) {
            $project = Project::where('slug', $slug)->first();
            if (! $project) {
                $this->command->warn("Project slug tidak ditemukan: {$slug}");
                continue;
            }
            $src = $srcDir.'/'.$file;
            if (! is_file($src)) {
                $this->command->warn("File sumber tidak ada: {$file}");
                continue;
            }
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $destName = $slug.'.'.$ext;
            copy($src, $destDir.'/'.$destName);

            $relative = 'storage/projects-sync/'.$destName;
            if (function_exists('optimizeImage')) {
                optimizeImage($relative, 1600, 82);
            }

            $project->images()->where('type', 'cover')->each(function ($img) {
                if (function_exists('deleteFile') && ! str_contains((string) $img->image, 'projects-sync/')) {
                    deleteFile($img->image);
                }
                $img->delete();
            });

            $project->images()->create([
                'image' => $relative,
                'type' => 'cover',
                'sort_order' => 0,
            ]);
        }

        $this->command->info('Portfolio images synced successfully.');
    }
}
