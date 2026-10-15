<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\PageView;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait BuildsPageStats
{
    /**
     * Halaman terpopuler pada rentang waktu (semua path).
     */
    protected function topPages(Carbon $from, int $limit = 6): array
    {
        return $this->pageRows($from, null, $limit);
    }

    /**
     * Halaman portofolio paling banyak dikunjungi (listing + detail projek).
     */
    protected function portfolioPages(Carbon $from, int $limit = 5): array
    {
        return $this->pageRows($from, 'portfolio', $limit);
    }

    /**
     * Group by page_path terurut jumlah kunjungan, dengan opsi filter prefix path.
     */
    protected function pageRows(Carbon $from, ?string $pathPrefix, int $limit): array
    {
        $rows = PageView::query()
            ->where('created_at', '>=', $from)
            ->whereNotNull('page_path')
            ->when($pathPrefix !== null, fn ($q) => $q->where('page_path', 'like', $pathPrefix . '%'))
            ->select('page_path', DB::raw('COUNT(*) as total'))
            ->groupBy('page_path')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return $this->pageNames($rows);
    }

    /**
     * Tambahkan nama ramah pada hasil group by page_path:
     * path statis dikenal dipetakan manual, slug portofolio dipetakan ke nama projek.
     */
    protected function pageNames(Collection $rows): array
    {
        $projectNames = Project::query()->pluck('name', 'slug');
        $staticNames = [
            '/' => 'Beranda',
            'portfolio' => 'Portofolio',
            'layanan' => 'Layanan',
            'tentang-kami' => 'Tentang Kami',
            'faq' => 'FAQ',
            'kontak' => 'Kontak',
            'kebijakan-privasi' => 'Kebijakan Privasi',
            'syarat-ketentuan' => 'Syarat & Ketentuan',
        ];

        return $rows->map(function ($row) use ($staticNames, $projectNames) {
            $path = $row->page_path;
            $isPortfolio = str_starts_with($path, 'portfolio');

            if (isset($staticNames[$path])) {
                $name = $staticNames[$path];
            } elseif (preg_match('#^portfolio/([^/?]+)#', $path, $m)) {
                $name = $projectNames[$m[1]] ?? ('Portofolio: ' . $m[1]);
            } else {
                $name = $path;
            }

            return [
                'path' => $path,
                'name' => $name,
                'total' => (int) $row->total,
                'is_portfolio' => $isPortfolio,
            ];
        })->values()->all();
    }
}
