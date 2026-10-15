<?php

namespace App\Console\Commands;

use App\Http\Controllers\CvController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class ExportCv extends Command
{
    protected $signature = 'cv:export {--domain= : URL portfolio publik yang ditulis di dalam CV (misal https://domain-anda.com)}';

    protected $description = 'Ekspor CV Indonesia + Inggris ke public/cv-files sebagai lampiran lamaran kerja';

    public function handle(CvController $cv): int
    {
        $request = Request::create('/');
        if ($domain = $this->option('domain')) {
            $request->query->set('domain', rtrim($domain, '/'));
        }

        // Catatan: jangan pakai public/cv — menutupi route dinamis GET /cv pada php artisan serve.
        $dir = public_path('cv-files');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        Pdf::loadView('cv.id', $cv->data('id', $request))->save($dir . '/' . CvController::FILENAME_ID);
        Pdf::loadView('cv.en', $cv->data('en', $request))->save($dir . '/' . CvController::FILENAME_EN);
        forgetSettingsCache();

        $this->info('CV diekspor ke public/cv-files/' . CvController::FILENAME_ID . ' dan public/cv-files/' . CvController::FILENAME_EN);

        return self::SUCCESS;
    }
}
