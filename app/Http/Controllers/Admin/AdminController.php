<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsPageStats;
use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    use BuildsPageStats;

    public function dashboard()
    {
        $cacheKey = 'admin.dashboard.' . now()->format('YmdHi');
        $stats = Cache::remember($cacheKey, 60, function () {
            return $this->buildStats();
        });

        $latestMessages = Cache::remember('admin.latestMessages', 60, function () {
            return \App\Models\Message::latest()->take(5)->get();
        });

        return view('admin.dashboard', array_merge($stats, [
            'latestMessages' => $latestMessages,
        ]));
    }

    protected function buildStats(): array
    {
        $periodStats = $this->periodStats();

        $series = [
            'daily' => $this->seriesDaily(),
            'weekly' => $this->seriesWeekly(),
            'monthly' => $this->seriesMonthly(),
            'yearly' => $this->seriesYearly(),
        ];

        $topPages = [
            'daily' => $this->topPages(now()->subDays(29)->startOfDay()),
            'weekly' => $this->topPages(now()->subWeeks(11)->startOfWeek()),
            'monthly' => $this->topPages(now()->subMonthsNoOverflow(11)->startOfMonth()),
            'yearly' => $this->topPages(now()->subYears(4)->startOfYear()),
        ];

        $portfolioTop = [
            'daily' => $this->portfolioPages(now()->subDays(29)->startOfDay()),
            'weekly' => $this->portfolioPages(now()->subWeeks(11)->startOfWeek()),
            'monthly' => $this->portfolioPages(now()->subMonthsNoOverflow(11)->startOfMonth()),
            'yearly' => $this->portfolioPages(now()->subYears(4)->startOfYear()),
        ];

        $contentSummary = [
            ['label' => 'Projek', 'count' => \App\Models\Project::count(), 'route' => route('admin.projects.index'), 'icon' => 'folder', 'colors' => ['bg' => 'from-blue-500 to-blue-600', 'iconBg' => 'bg-blue-50', 'iconText' => 'text-blue-600']],
            ['label' => 'Kategori Projek', 'count' => \App\Models\ProjectCategory::count(), 'route' => route('admin.project-categories.index'), 'icon' => 'tag', 'colors' => ['bg' => 'from-indigo-500 to-indigo-600', 'iconBg' => 'bg-indigo-50', 'iconText' => 'text-indigo-600']],
            ['label' => 'FAQ', 'count' => \App\Models\Faq::count(), 'route' => route('admin.faqs.index'), 'icon' => 'help', 'colors' => ['bg' => 'from-amber-500 to-amber-600', 'iconBg' => 'bg-amber-50', 'iconText' => 'text-amber-600']],
            ['label' => 'Pesan Masuk', 'count' => \App\Models\Message::count(), 'route' => route('admin.messages.index'), 'icon' => 'mail', 'colors' => ['bg' => 'from-slate-500 to-slate-600', 'iconBg' => 'bg-slate-100', 'iconText' => 'text-slate-600']],
        ];
        $totalMessages = \App\Models\Message::count();
        $unreadMessages = \App\Models\Message::where('is_read', false)->count();

        return compact(
            'periodStats', 'series', 'topPages', 'portfolioTop',
            'contentSummary', 'totalMessages', 'unreadMessages'
        );
    }

    /**
     * Statistik per periode laporan (harian/mingguan/bulanan/tahunan).
     * Window-nya sengaja disamakan dengan window chart agar kartu KPI,
     * grafik, dan halaman terpopuler berbicara satu suara.
     */
    protected function periodStats(): array
    {
        $pct = fn (int $current, int $prev) => $prev > 0 ? round((($current - $prev) / $prev) * 100) : ($current > 0 ? 100 : 0);
        $share = fn (int $part, int $whole) => $whole > 0 ? round($part / $whole * 100) : 0;

        $windows = [
            'daily' => [now()->subDays(29)->startOfDay(), null, now()->subDays(59)->startOfDay(), now()->subDays(30)->endOfDay()],
            'weekly' => [now()->subWeeks(11)->startOfWeek(), null, now()->subWeeks(23)->startOfWeek(), now()->subWeeks(12)->endOfWeek()],
            'monthly' => [now()->subMonthsNoOverflow(11)->startOfMonth(), null, now()->subMonthsNoOverflow(23)->startOfMonth(), now()->subMonthsNoOverflow(12)->endOfMonth()],
            'yearly' => [now()->subYears(4)->startOfYear(), null, now()->subYears(9)->startOfYear(), now()->subYears(5)->endOfYear()],
        ];

        $stats = [];
        foreach ($windows as $key => [$from, $to, $prevFrom, $prevTo]) {
            $current = $this->windowCounts($from, $to);
            $previous = $this->windowCounts($prevFrom, $prevTo);

            $stats[$key] = [
                'visits' => $current['total'],
                'visitsChange' => $pct($current['total'], $previous['total']),
                'unique' => $current['unique'],
                'portfolio' => $current['portfolio'],
                'portfolioChange' => $pct($current['portfolio'], $previous['portfolio']),
                'share' => $share($current['portfolio'], $current['total']),
            ];
        }

        $stats['visitors_total'] = PageView::count();
        $stats['unique_total'] = PageView::distinct('ip_address')->count('ip_address');

        return $stats;
    }

    /**
     * Total, portofolio, dan pengunjung unik dalam satu query untuk satu rentang.
     */
    protected function windowCounts(Carbon $from, ?Carbon $to = null): array
    {
        $row = PageView::query()
            ->where('created_at', '>=', $from)
            ->when($to !== null, fn ($q) => $q->where('created_at', '<=', $to))
            ->selectRaw("COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_visitors, COALESCE(SUM(CASE WHEN page_path LIKE 'portfolio%' THEN 1 ELSE 0 END), 0) as portfolio")
            ->first();

        return [
            'total' => (int) $row->total,
            'unique' => (int) $row->unique_visitors,
            'portfolio' => (int) $row->portfolio,
        ];
    }

    /**
     * Seri teragregasi per bucket waktu; satu query menghasilkan ketiga garis
     * sparkline (total, unik, portofolio), tanpa N+1 per titik.
     */
    protected function combinedSeries(string $expression, Carbon $from, array $labels, array $keys): array
    {
        $rows = PageView::query()
            ->where('created_at', '>=', $from)
            ->selectRaw("{$expression} as bucket, COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_visitors, COALESCE(SUM(CASE WHEN page_path LIKE 'portfolio%' THEN 1 ELSE 0 END), 0) as portfolio")
            ->groupBy('bucket')
            ->get()
            ->keyBy('bucket');

        $total = [];
        $unique = [];
        $portfolio = [];
        foreach ($keys as $key) {
            $row = $rows[$key] ?? null;
            $total[] = (int) ($row->total ?? 0);
            $unique[] = (int) ($row->unique_visitors ?? 0);
            $portfolio[] = (int) ($row->portfolio ?? 0);
        }

        return ['labels' => $labels, 'total' => $total, 'unique' => $unique, 'portfolio' => $portfolio];
    }

    protected function seriesDaily(): array
    {
        $days = collect(range(29, 0));
        return $this->combinedSeries(
            'DATE(created_at)',
            now()->subDays(29)->startOfDay(),
            $days->map(fn ($i) => now()->subDays($i)->format('d/m'))->values()->all(),
            $days->map(fn ($i) => now()->subDays($i)->toDateString())->values()->all()
        );
    }

    protected function seriesWeekly(): array
    {
        $starts = collect(range(11, 0))->map(fn ($i) => now()->subWeeks($i)->startOfWeek());
        return $this->combinedSeries(
            'YEARWEEK(created_at, 3)',
            now()->subWeeks(11)->startOfWeek(),
            $starts->map(fn ($d) => 'Minggu ' . $d->isoFormat('DD/MM'))->values()->all(),
            $starts->map(fn ($d) => $d->format('oW'))->values()->all()
        );
    }

    protected function seriesMonthly(): array
    {
        $months = collect(range(11, 0));
        return $this->combinedSeries(
            "DATE_FORMAT(created_at, '%Y-%m')",
            now()->subMonthsNoOverflow(11)->startOfMonth(),
            $months->map(fn ($i) => now()->subMonthsNoOverflow($i)->isoFormat('MMM YY'))->values()->all(),
            $months->map(fn ($i) => now()->subMonthsNoOverflow($i)->format('Y-m'))->values()->all()
        );
    }

    protected function seriesYearly(): array
    {
        $years = collect(range(4, 0));
        return $this->combinedSeries(
            'YEAR(created_at)',
            now()->subYears(4)->startOfYear(),
            $years->map(fn ($i) => (string) now()->subYears($i)->year)->values()->all(),
            $years->map(fn ($i) => now()->subYears($i)->year)->values()->all()
        );
    }
}
