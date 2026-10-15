@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

<x-admin.breadcrumb :items="[['label' => 'Dashboard']]" />

<x-admin.section-title title="Dashboard" subtitle="Laporan kunjungan website dan halaman portofolio">
    <x-slot:actions>
        <span class="text-xs text-[#94a3b8] hidden md:inline">{{ now()->translatedFormat('l, d F Y') }}</span>
    </x-slot:actions>
</x-admin.section-title>

@php
$init = $periodStats['daily'];
$periods = [
    'daily' => ['label' => 'Harian', 'unit' => 'hari'],
    'weekly' => ['label' => 'Mingguan', 'unit' => 'minggu'],
    'monthly' => ['label' => 'Bulanan', 'unit' => 'bulan'],
    'yearly' => ['label' => 'Tahunan', 'unit' => 'tahun'],
];
// Rentang tanggal aktif per periode untuk bar info (pola bar info juneo-phone)
$periodMeta = [
    'daily' => ['range' => now()->subDays(29)->translatedFormat('d M') . ' - ' . now()->translatedFormat('d M Y'), 'note' => 'Laporan harian, 30 hari terakhir'],
    'weekly' => ['range' => now()->subWeeks(11)->startOfWeek()->translatedFormat('d M') . ' - ' . now()->translatedFormat('d M Y'), 'note' => 'Laporan mingguan, 12 minggu terakhir'],
    'monthly' => ['range' => now()->subMonthsNoOverflow(11)->startOfMonth()->translatedFormat('M Y') . ' - ' . now()->translatedFormat('M Y'), 'note' => 'Laporan bulanan, 12 bulan terakhir'],
    'yearly' => ['range' => now()->subYears(4)->startOfYear()->format('Y') . ' - ' . now()->format('Y'), 'note' => 'Laporan tahunan, 5 tahun terakhir'],
];
$iconVisits = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
$iconUnique = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
$iconPortfolio = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>';
$iconShare = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>';
$iconCalendar = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
@endphp

<div x-data="dashboard({
    series: @js($series),
    topPages: @js($topPages),
    portfolioTop: @js($portfolioTop),
    stats: @js($periodStats),
    periodMeta: @js($periodMeta),
    units: @js(collect($periods)->map(fn ($p) => $p['unit'])->all()),
})">

    {{-- Bar filter periode (pola juneo-phone): satu-satunya pengendali kartu, grafik, dan halaman --}}
    <div class="bg-white border border-gray-200 border-l-4 border-l-blue-600 rounded-xl shadow-sm p-3.5 mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-bold text-[#1e293b]">Periode Laporan</p>
            <p class="text-[11px] text-[#94a3b8] mt-0.5">Satu pilihan mengubah semua widget di bawah.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($periods as $key => $p)
            <button type="button" @click="setPeriod('{{ $key }}')"
                    :class="period === '{{ $key }}'
                        ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-sm border-transparent'
                        : 'bg-white border-gray-200 text-[#64748b] hover:bg-gray-50'"
                    class="px-3.5 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
                {{ $p['label'] }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Bar info rentang tanggal aktif --}}
    <div class="admin-card px-4 py-3 mb-6 flex flex-wrap items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            {!! $iconCalendar !!}
        </div>
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-bold text-[#0f172a]" x-text="meta().range"></span>
                <span class="admin-badge admin-badge-soft-blue">Berjalan</span>
            </div>
            <span class="block text-[11px] text-[#94a3b8] mt-0.5" x-text="meta().note"></span>
        </div>
        <span class="sm:ml-auto text-[11px] text-[#94a3b8] hidden md:block">Data kunjungan diperbarui setiap beberapa menit</span>
    </div>

    {{-- KPI row: 4 kartu dengan count-up dan sparkline, ikut periode --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card label="Kunjungan Website" :value="number_format($init['visits'])" tone="blue" :icon="$iconVisits"
            value-bind="fmt(disp.visits)" delta-bind="cur.visitsChange" spark-bind="sparkSeries('total')"
            footer="Pengunjung unik {{ number_format($init['unique']) }}" footer-bind="'Pengunjung unik ' + fmt(cur.unique)" />
        <x-admin.stat-card label="Pengunjung Unik" :value="number_format($init['unique'])" tone="teal" :icon="$iconUnique"
            value-bind="fmt(disp.unique)" hint="berdasarkan alamat IP" spark-bind="sparkSeries('unique')" />
        <x-admin.stat-card label="Kunjungan Portofolio" :value="number_format($init['portfolio'])" tone="green" :icon="$iconPortfolio"
            value-bind="fmt(disp.portfolio)" delta-bind="cur.portfolioChange" spark-bind="sparkSeries('portfolio')" />
        <x-admin.stat-card label="Porsi Portofolio" :value="$init['share'] . '%'" tone="violet" :icon="$iconShare"
            value-bind="disp.share + '%'" footer="dari {{ number_format($init['visits']) }} kunjungan" footer-bind="'dari ' + fmt(cur.visits) + ' kunjungan'"
            spark-bind="shareSeries" />
    </div>

    {{-- Grafik tren kombinasi: total vs portofolio --}}
    <div class="admin-card p-5 sm:p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-blue-600 to-blue-400 shrink-0"></span>
            <div class="min-w-0">
                <h3 class="text-base font-bold text-[#1e293b] leading-tight">Tren Kunjungan Website vs Portofolio</h3>
                <p class="text-[11px] text-[#94a3b8] mt-0.5" x-text="subtitleText"></p>
            </div>
        </div>
        <div class="relative w-full" style="height: 300px;">
            <canvas x-ref="trend"></canvas>
        </div>
    </div>

    {{-- Dua kolom: halaman terpopuler + drill-down portofolio --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        <div class="lg:col-span-3 admin-card p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3 mb-1">
                <div class="flex items-center gap-2">
                    <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-blue-600 to-blue-400 shrink-0"></span>
                    <h3 class="text-base font-bold text-[#1e293b]">Halaman Terpopuler</h3>
                </div>
            </div>
            <p class="text-[11px] text-[#94a3b8] mb-4">6 halaman dengan kunjungan terbanyak pada periode ini.</p>

            <div x-cloak x-show="activePages().length === 0" class="text-sm text-[#94a3b8] text-center py-10">
                Belum ada data kunjungan halaman.
            </div>
            <div x-show="activePages().length > 0">
                <template x-for="(p, i) in activePages()" :key="period + ':' + p.path">
                    <div class="flex items-center gap-3 py-3" :class="i < activePages().length - 1 ? 'border-b border-gray-50' : ''">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold shrink-0"
                              :class="i === 0 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500'" x-text="i + 1"></span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-[#1e293b] truncate" x-text="p.name"></span>
                                <span x-show="p.is_portfolio" class="admin-badge admin-badge-soft-green text-[9px] uppercase shrink-0">Portofolio</span>
                            </div>
                            <span class="block text-[11px] text-[#94a3b8] font-mono truncate" x-text="'/' + p.path"></span>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-sm font-bold text-[#0f172a] tabular-nums" x-text="fmt(p.total)"></div>
                            <div class="text-[10px] text-[#94a3b8]">kunjungan</div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="lg:col-span-2 admin-card p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3 mb-1">
                <div class="flex items-center gap-2">
                    <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-emerald-600 to-emerald-400 shrink-0"></span>
                    <h3 class="text-base font-bold text-[#1e293b]">Fokus Portofolio</h3>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 no-underline">Kelola projek &rarr;</a>
            </div>
            <p class="text-[11px] text-[#94a3b8] mb-4" x-text="'Halaman portofolio paling ramai. Total periode ini: ' + fmt(cur.portfolio) + ' kunjungan.'"></p>

            <div x-cloak x-show="activePortfolio().length === 0" class="text-sm text-[#94a3b8] text-center py-10">
                Belum ada kunjungan halaman portofolio.
            </div>
            <div x-show="activePortfolio().length > 0">
                <template x-for="(p, i) in activePortfolio()" :key="period + ':' + p.path">
                    <div class="flex items-center gap-3 py-3" :class="i < activePortfolio().length - 1 ? 'border-b border-gray-50' : ''">
                        <div class="flex-1 min-w-0">
                            <span class="block text-sm font-semibold text-[#1e293b] truncate" x-text="p.name"></span>
                            <div class="mt-1.5 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full" :style="'width:' + portfolioShare(p) + '%; background: var(--admin-portfolio, #059669);'"></div>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-sm font-bold text-[#0f172a] tabular-nums" x-text="fmt(p.total)"></div>
                            <div class="text-[10px] text-[#94a3b8]" x-text="portfolioShare(p) + '% dari portofolio'"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- Widget non-periode --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
    <div class="lg:col-span-2 admin-card p-5 sm:p-6">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-blue-600 to-blue-400 shrink-0"></span>
                <h3 class="text-base font-bold text-[#1e293b]">Pesan Masuk Terbaru</h3>
            </div>
            @if($unreadMessages > 0)
            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadMessages }} belum dibaca</span>
            @endif
        </div>
        <p class="text-[11px] text-[#94a3b8] mb-4">Dari total {{ number_format($totalMessages) }} pesan.</p>
        @if($latestMessages->isEmpty())
        <div class="text-sm text-[#94a3b8] text-center py-10">Belum ada pesan masuk.</div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($latestMessages as $msg)
            <a href="{{ route('admin.messages.show', $msg) }}" class="flex items-start gap-3 py-3 no-underline group">
                <div class="w-9 h-9 rounded-full {{ $msg->is_read ? 'bg-gray-100 text-[#64748b]' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-[#1e293b] truncate">{{ $msg->name }}</span>
                        <span class="text-[10px] text-[#94a3b8] shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-[#64748b] line-clamp-1">{{ $msg->message }}</p>
                </div>
                @if(!$msg->is_read)
                <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></span>
                @endif
            </a>
            @endforeach
        </div>
        <a href="{{ route('admin.messages.index') }}" class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-700 no-underline">Lihat semua pesan &rarr;</a>
        @endif
    </div>

    <div class="lg:col-span-3">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-blue-600 to-blue-400 shrink-0"></span>
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-[#1e293b] leading-tight">Ringkasan Konten</h3>
                    <p class="text-[11px] text-[#94a3b8] mt-0.5">Kelola konten utama website Anda dengan cepat.</p>
                </div>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 no-underline">Atur Website &rarr;</a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($contentSummary as $s)
            <a href="{{ $s['route'] }}" class="group admin-card hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-4 flex items-center gap-3 no-underline">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $s['colors']['bg'] }} flex items-center justify-center text-white shrink-0">
                    <x-admin.nav-icon :name="$s['icon']" />
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-extrabold text-[#0f172a] leading-none">{{ number_format($s['count']) }}</div>
                    <div class="text-[11px] font-medium text-[#64748b] truncate">{{ $s['label'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', function () {
    Alpine.data('dashboard', function (passed) {
        return {
            period: 'daily',
            chart: null,
            // Data dari server wajib dipetakan eksplisit ke scope;
            // Alpine hanya memakai return value factory sebagai scope,
            // argumen x-data tidak otomatis tersedia lewat this.
            series: passed.series || {},
            topPages: passed.topPages || {},
            portfolioTop: passed.portfolioTop || {},
            stats: passed.stats || {},
            units: passed.units || {},
            periodMeta: passed.periodMeta || {},
            disp: { visits: 0, unique: 0, portfolio: 0, share: 0 },
            get cur() { return this.stats[this.period]; },
            get subtitleText() {
                const s = this.series[this.period];
                const st = this.bucketStats(s.total);
                return 'Rata-rata ' + this.fmt(st.avg) + ' kunjungan per ' + (this.units[this.period] || 'hari')
                    + ', puncak ' + this.fmt(st.max) + (st.maxLabel !== '-' ? ' (' + st.maxLabel + ')' : '');
            },
            fmt(n) { return Number(n || 0).toLocaleString('id-ID'); },
            activePages() { return this.topPages[this.period] || []; },
            activePortfolio() { return this.portfolioTop[this.period] || []; },
            portfolioShare(p) {
                const total = this.cur.portfolio || 0;
                return total > 0 ? Math.round((p.total / total) * 100) : 0;
            },
            bucketStats(values) {
                if (!values || !values.length) return { avg: 0, max: 0, maxLabel: '-' };
                const max = Math.max(...values);
                return {
                    avg: Math.round(values.reduce((a, b) => a + b, 0) / values.length),
                    max: max,
                    maxLabel: values.length ? (this.series[this.period].labels[values.indexOf(max)] || '-') : '-',
                };
            },
            meta() { return this.periodMeta[this.period] || { range: '', note: '' }; },
            sparkSeries(key) { return (this.series[this.period] || {})[key] || []; },
            get shareSeries() {
                const s = this.series[this.period] || { total: [], portfolio: [] };
                return s.total.map((t, i) => t > 0 ? Math.round((s.portfolio[i] / t) * 100) : 0);
            },
            sparkPoints(a) {
                if (!a || a.length < 2) return '0,15 200,15';
                const min = Math.min(...a);
                const max = Math.max(...a);
                const range = (max - min) || 1;
                const step = 200 / (a.length - 1);
                return a.map((v, i) =>
                    (i * step).toFixed(1) + ',' + (26 - ((v - min) / range) * 22).toFixed(1)
                ).join(' ');
            },
            sparkFill(a) {
                if (!a || a.length < 2) return '';
                return this.sparkPoints(a) + ' 200,30 0,30';
            },
            animateKpi(key, to) {
                const from = Number(this.disp[key]) || 0;
                const start = performance.now();
                const duration = 800;
                const step = (now) => {
                    const t = Math.min((now - start) / duration, 1);
                    const eased = 1 - (1 - t) * (1 - t);
                    this.disp[key] = Math.round(from + (to - from) * eased);
                    if (t < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            },
            animateAll(stats) {
                ['visits', 'unique', 'portfolio', 'share'].forEach((k) => {
                    this.animateKpi(k, Number(stats[k]) || 0);
                });
            },
            init() {
                this.initChart();
                this.animateAll(this.cur);
            },
            initChart() {
                const s = this.series[this.period];
                this.chart = new Chart(this.$refs.trend.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: s.labels,
                        datasets: [
                            {
                                label: 'Total Kunjungan',
                                data: s.total,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.06)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointBackgroundColor: '#2563eb',
                                borderJoinStyle: 'round',
                                borderCapStyle: 'round',
                            },
                            {
                                label: 'Portofolio',
                                data: s.portfolio,
                                borderColor: '#059669',
                                backgroundColor: 'rgba(5, 150, 105, 0.10)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointBackgroundColor: '#059669',
                                borderJoinStyle: 'round',
                                borderCapStyle: 'round',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    font: { size: 11, family: 'Inter' },
                                    color: '#64748b',
                                },
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 11, family: 'Inter' },
                                bodyFont: { size: 12, family: 'Inter' },
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function (ctx) {
                                        const value = Number(ctx.parsed.y).toLocaleString('id-ID');
                                        return ctx.datasetIndex === 0
                                            ? ' Total: ' + value + ' kunjungan'
                                            : ' Portofolio: ' + value + ' kunjungan';
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                border: { display: false },
                                ticks: { font: { size: 10, family: 'Inter' }, color: '#94a3b8', precision: 0 },
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { maxTicksLimit: 8, font: { size: 9, family: 'Inter' }, color: '#94a3b8' },
                            },
                        },
                    },
                });
            },
            setPeriod(p) {
                if (this.period === p) return;
                this.period = p;
                const s = this.series[p];
                if (s && this.chart) {
                    this.chart.data.labels = s.labels;
                    this.chart.data.datasets[0].data = s.total;
                    this.chart.data.datasets[1].data = s.portfolio;
                    this.chart.update();
                }
                this.animateAll(this.cur);
            },
        };
    });
});
</script>
@endpush
@endsection
