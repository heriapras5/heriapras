<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\TimelineItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public const FILENAME_ID = 'CV-Heri-Agus-Prasetyo-ID.pdf';
    public const FILENAME_EN = 'CV-Heri-Agus-Prasetyo-EN.pdf';

    public function downloadId(Request $request)
    {
        if ($external = $this->externalCvUrl()) {
            return redirect()->away($external);
        }

        return $this->pdf('cv.id', $this->data('id', $request), self::FILENAME_ID);
    }

    public function downloadEn(Request $request)
    {
        if ($external = $this->externalCvUrl()) {
            return redirect()->away($external);
        }

        return $this->pdf('cv.en', $this->data('en', $request), self::FILENAME_EN);
    }

    public function previewId(Request $request)
    {
        return view('cv.id', $this->data('id', $request));
    }

    public function previewEn(Request $request)
    {
        return view('cv.en', $this->data('en', $request));
    }

    protected function externalCvUrl(): ?string
    {
        $url = trim((string) setting('cv_url', ''));

        if ($url !== '' && str_starts_with($url, 'http')) {
            return $url;
        }

        return null;
    }

    protected function pdf(string $view, array $data, string $filename)
    {
        return Pdf::loadView($view, $data)->download($filename);
    }

    /**
     * Rakit data CV dari database (settings, timeline, services, projects).
     * Nilai fallback berasal dari CV lama agar PDF tetap valid saat
     * pengaturan admin belum diisi.
     */
    public function data(string $lang, Request $request): array
    {
        $en = $lang === 'en';
        $portfolioSetting = trim((string) setting('portfolio_url', ''));
        $requestDomain = $request->query('domain') ? rtrim((string) $request->query('domain'), '/') : null;
        $defaultUrl = $requestDomain ?: url('/');
        $isLocal = (bool) preg_match('#^https?://(localhost|127\.|10\.|192\.168\.|.*\.test|.*\.local)#i', $defaultUrl);
        $portfolioUrl = $portfolioSetting ?: ($requestDomain ?: ($isLocal ? null : $defaultUrl));

        $skillGroups = Service::where('type', 'skill')
            ->orderBy('sort_order')
            ->get(['group_name', 'title'])
            ->groupBy('group_name')
            ->map(fn ($rows, $group) => [
                'label' => $this->skillGroupLabel($group, $en),
                'items' => $rows->pluck('title')->values()->all(),
            ])
            ->values()
            ->all();

        if (empty($skillGroups)) {
            $skillGroups = $this->fallbackSkills($en);
        }

        return [
            'name' => setting('full_name', 'Heri Agus Prasetyo'),
            'roleLabel' => $en ? 'Fullstack Web Developer' : 'Fullstack Web Developer',
            'location' => setting('location', 'Yogyakarta, Indonesia'),
            'email' => setting('footer_email') ?: 'heriapras5@gmail.com',
            'phoneDisplay' => '+62 851-5692-0786',
            'waNumber' => $this->waNumber(),
            'github' => setting('github_url') ?: 'https://github.com/heriapras5',
            'linkedin' => setting('social_linkedin') ?: null,
            'portfolioUrl' => $portfolioUrl,
            'summary' => $en ? $this->summaryEn() : $this->summaryId(),
            'skillGroups' => $skillGroups,
            'experience' => $this->timeline('experience', $en),
            'projects' => $this->selectedProjects($en),
            'education' => $this->timeline('education', $en),
            'organizations' => $en ? $this->organizationsEn() : $this->organizationsId(),
        ];
    }

    protected function waNumber(): string
    {
        $raw = preg_replace('/\D/', '', (string) (setting('whatsapp_number') ?: '6285156920786'));

        if (str_starts_with($raw, '08')) {
            $raw = '62' . substr($raw, 1);
        }

        return $raw;
    }

    protected function skillGroupLabel(string $group, bool $en): string
    {
        $map = ['Frontend' => 'Frontend', 'Backend' => 'Backend', 'Database' => $en ? 'Database' : 'Basis Data', 'Tools' => $en ? 'Tools' : 'Peralatan', 'Layanan' => 'Services'];

        return $map[$group] ?? $group;
    }

    protected function fallbackSkills(bool $en): array
    {
        return [
            ['label' => 'Frontend', 'items' => ['HTML', 'CSS', 'JavaScript', 'Bootstrap', 'Tailwind CSS', 'Alpine.js']],
            ['label' => 'Backend', 'items' => ['PHP', 'Laravel', 'CodeIgniter', 'REST API']],
            ['label' => $en ? 'Database' : 'Basis Data', 'items' => ['MySQL']],
            ['label' => $en ? 'Tools' : 'Peralatan', 'items' => ['Git', 'GitHub', 'VS Code']],
        ];
    }

    protected function timeline(string $kind, bool $en): array
    {
        $query = TimelineItem::where('is_active', true)->orderBy('sort_order');
        $items = $kind === 'education'
            ? $query->where('type', 'education')->get()
            : $query->where('type', '!=', 'education')->get();

        if ($items->isEmpty()) {
            return $kind === 'education'
                ? ($en ? $this->fallbackEducationEn() : $this->fallbackEducationId())
                : ($en ? $this->fallbackExperienceEn() : $this->fallbackExperienceId());
        }

        return $items->map(fn ($item) => [
            'title' => $item->title,
            'org' => $item->organization ?: '-',
            'period' => $item->period ?: '-',
            'desc' => $item->description ?: '',
        ])->all();
    }

    protected function selectedProjects(bool $en): array
    {
        $slugs = [
            'smopf-sistem-manajemen-order-produksi-furniture',
            'aplikasi-manajemen-service-hp-juneo-phone',
            'website-duyufuna',
            'website-rdr-interior-aluminium',
        ];

        $projects = Project::with(['categories', 'technologies'])
            ->whereIn('slug', $slugs)
            ->get()
            ->sortBy(fn ($p) => array_search($p->slug, $slugs))
            ->values();

        if ($projects->isEmpty()) {
            $projects = Project::with(['categories', 'technologies'])
                ->orderBy('created_at', 'desc')->limit(4)->get();
        }

        return $projects->map(fn ($project) => [
            'name' => $project->name,
            'meta' => trim(($project->categories->pluck('name')->implode(', ') ?: 'Project') . ($project->year ? ' - ' . $project->year : '')),
            'tech' => $project->technologies->pluck('name')->values()->all() ?: ['Laravel', 'PHP', 'MySQL'],
            'bullets' => $this->projectBullets($project->slug, $en),
        ])->all();
    }

    protected function projectBullets(string $slug, bool $en): array
    {
        $id = [
            'smopf-sistem-manajemen-order-produksi-furniture' => [
                'Membangun alur order, produksi, QC, dan laporan dalam satu panel admin.',
                'Menyediakan ekspor laporan PDF dan Excel untuk kebutuhan operasional.',
            ],
            'aplikasi-manajemen-service-hp-juneo-phone' => [
                'Mengelola data pelanggan, antrian service, biaya, dan laporan keuangan harian.',
                'Menyediakan dashboard dan pencatatan pengeluaran serta fee karyawan.',
            ],
            'website-duyufuna' => [
                'Company profile dan katalog layanan dengan halaman detail dan galeri.',
                'Dilengkapi integrasi WhatsApp dan panel admin untuk pembaruan konten.',
            ],
            'website-rdr-interior-aluminium' => [
                'Katalog digital produk interior aluminium custom seperti kitchen set, pintu, dan partisi.',
                'Halaman detail produk dengan integrasi WhatsApp untuk pemesanan.',
            ],
        ];

        $enBullets = [
            'smopf-sistem-manajemen-order-produksi-furniture' => [
                'Built order, production, QC, and reporting flows in a single admin panel.',
                'Provided PDF and Excel report exports for operational needs.',
            ],
            'aplikasi-manajemen-service-hp-juneo-phone' => [
                'Managed customer data, service queues, costs, and daily financial reports.',
                'Provided a dashboard with expense tracking and employee fee records.',
            ],
            'website-duyufuna' => [
                'Company profile and service catalog with detail pages and a gallery.',
                'Equipped with WhatsApp integration and an admin panel for content updates.',
            ],
            'website-rdr-interior-aluminium' => [
                'Digital catalog of custom aluminium interior products such as kitchen sets, doors, and partitions.',
                'Product detail pages with WhatsApp integration for orders.',
            ],
        ];

        $map = $en ? $enBullets : $id;

        return $map[$slug] ?? ($en
            ? ['Built with Laravel, from planning and database design to deployment.']
            : ['Dibangun dengan Laravel, dari perencanaan dan desain database hingga deployment.']);
    }

    protected function summaryId(): string
    {
        return 'Fullstack Web Developer dengan latar belakang S1 Sistem Informasi (Universitas Mercu Buana Yogyakarta, lulus 2023, IPK 3.34). Membangun 8 website dan sistem digital berbasis Laravel, dari company profile dan katalog produk hingga sistem manajemen order-produksi dan aplikasi service HP. Terbiasa mengerjakan alur lengkap: perencanaan, frontend, backend, database, panel admin, hingga deployment.';
    }

    protected function summaryEn(): string
    {
        return 'Fullstack Web Developer with a Bachelor degree in Information Systems (Mercu Buana University Yogyakarta, 2023, GPA 3.34). Built 8 Laravel-based websites and digital systems, from company profiles and product catalogs to an order-production management system and a phone service application. Comfortable owning the full cycle: planning, frontend, backend, database, admin panel, and deployment.';
    }

    protected function fallbackEducationId(): array
    {
        return [
            ['title' => 'S1 Sistem Informasi', 'org' => 'Universitas Mercu Buana Yogyakarta', 'period' => 'Lulus 2023 - IPK 3.34', 'desc' => 'Sistem informasi, pengembangan aplikasi, database, dan analisis sistem.'],
            ['title' => 'SMK Negeri 01 Bangsri (RPL)', 'org' => 'Rekayasa Perangkat Lunak', 'period' => 'Lulus 2016', 'desc' => 'Dasar pemrograman dan pengembangan web.'],
        ];
    }

    protected function fallbackEducationEn(): array
    {
        return [
            ['title' => 'Bachelor of Information Systems', 'org' => 'Mercu Buana University Yogyakarta', 'period' => 'Graduated 2023 - GPA 3.34', 'desc' => 'Information systems, application development, databases, and systems analysis.'],
            ['title' => 'SMK Negeri 01 Bangsri (Software Engineering)', 'org' => 'Software Engineering', 'period' => 'Graduated 2016', 'desc' => 'Programming fundamentals and web development.'],
        ];
    }

    protected function fallbackExperienceId(): array
    {
        return [
            ['title' => 'Web Developer (Berbasis Project)', 'org' => 'Freelance', 'period' => 'Berbasis Project', 'desc' => 'Membangun website company profile, katalog produk, dan sistem manajemen untuk kebutuhan bisnis.'],
            ['title' => 'Kerja Praktek', 'org' => 'Fakultas Teknologi Informasi UMBY', 'period' => '2020 (6 bulan)', 'desc' => 'Mendukung kegiatan teknologi informasi di lingkungan fakultas.'],
            ['title' => 'Barista', 'org' => 'Zhoklat Indonesia', 'period' => '2021-2022', 'desc' => 'Menyajikan minuman serta melatih komunikasi dan kerja tim.'],
            ['title' => 'Magang', 'org' => 'Royyan Computer Mlonggo', 'period' => '2015 (3 bulan)', 'desc' => 'Membantu layanan komputer dan pengenalan dunia kerja.'],
        ];
    }

    protected function fallbackExperienceEn(): array
    {
        return [
            ['title' => 'Web Developer (Project-based)', 'org' => 'Freelance', 'period' => 'Project-based', 'desc' => 'Built company profile websites, product catalogs, and management systems for business needs.'],
            ['title' => 'Internship', 'org' => 'Faculty of Information Technology UMBY', 'period' => '2020 (6 months)', 'desc' => 'Supported information technology activities within the faculty.'],
            ['title' => 'Barista', 'org' => 'Zhoklat Indonesia', 'period' => '2021-2022', 'desc' => 'Served beverages while building communication and teamwork skills.'],
            ['title' => 'Apprentice', 'org' => 'Royyan Computer Mlonggo', 'period' => '2015 (3 months)', 'desc' => 'Assisted computer services and learned workplace basics.'],
        ];
    }

    protected function organizationsId(): array
    {
        return [
            'Anggota HMPSSI (Himpunan Mahasiswa Program Studi Sistem Informasi) UMBY, 2017-2019.',
            'Anggota UKMI AN-Nahl UMBY, 2017-2020.',
        ];
    }

    protected function organizationsEn(): array
    {
        return [
            'Member of HMPSSI (Information Systems Student Association) UMBY, 2017-2019.',
            'Member of UKMI AN-Nahl UMBY, 2017-2020.',
        ];
    }
}
