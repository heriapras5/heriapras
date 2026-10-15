<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $cache = Cache::remember('settings.all', 60, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return $cache[$key] ?? $default;
    }
}

if (! function_exists('faviconVersion')) {
    /**
     * Versi cache-buster favicon: berubah setiap favicon/logo/foto profil diganti,
     * agar tab browser mengambil ulang ikon baru.
     */
    function faviconVersion(): string
    {
        $stamp = 0;
        $rels = [];
        foreach (['profile_photo', 'favicon'] as $key) {
            $rel = (string) (setting($key) ?: '');
            $rels[] = $rel;
            if ($rel !== '') {
                $path = str_starts_with(ltrim($rel, '/'), 'storage/')
                    ? storage_path('app/public/' . substr(ltrim($rel, '/'), strlen('storage/')))
                    : public_path(ltrim($rel, '/'));
                if (is_file($path)) {
                    $stamp = max($stamp, (int) filemtime($path));
                }
            }
        }
        $earth = public_path('assets/img/favicon-earth.png');
        if (is_file($earth)) {
            $stamp = max($stamp, (int) filemtime($earth));
        }

        return md5(implode('|', $rels) . '|' . $stamp);
    }
}

if (! function_exists('forgetSettingsCache')) {
    function forgetSettingsCache(): void
    {
        Cache::forget('settings.all');
    }
}

if (! function_exists('deleteFile')) {
    function deleteFile(?string $path): void
    {
        if ($path) {
            $fullPath = public_path(ltrim($path, '/'));
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}

if (! function_exists('cleanRichText')) {
    /**
     * Bersihkan HTML dari editor rich text (Summernote) sebelum dirender mentah.
     * Hanya markup presentasi ringan yang dipertahankan; elemen aktif
     * (<script>, <iframe>, ...) beserta event handler dan protokol
     * javascript: selalu dibuang.
     */
    function cleanRichText(?string $html): string
    {
        if (! $html) {
            return '';
        }
        // Normalisasi spasi non-breaking Summernote agar paragraf
        // "kosong" seperti <p>&nbsp;</p> ikut terbuang di bawah.
        $html = preg_replace('/&nbsp;|&#160;/i', ' ', $html);

        // Buang elemen berbahaya beserta seluruh isinya.
        $html = preg_replace(
            '#<(script|iframe|object|embed|form|base|link|meta|style|noscript|template|frame|frameset|applet|bgsound|layer)[^>]*>.*?</\1\s*>#is',
            '',
            $html
        );
        // Sisa elemen berbahaya yang self-closing / tak berpasangan.
        $html = preg_replace(
            '#<(script|iframe|object|embed|form|base|link|meta|style|noscript|template|frame|frameset|applet|bgsound|layer)\b[^>]*/?>#i',
            '',
            $html
        );
        // Buang protokol berbahaya pada atribut URL (href="javascript:..." dsb).
        // Catatan: data:image:... (gambar tempel Summernote) tetap diizinkan,
        // hanya data:text/html yang dibuang.
        $html = preg_replace(
            '/\s+(href|src|xlink:href|action|formaction|poster|background|cite|data)\s*=\s*(["\'])\s*(javascript|vbscript|data\s*:\s*text\/html).*?\2/i',
            '',
            $html
        );
        $html = preg_replace(
            '/\s+(href|src|xlink:href|action|formaction|poster|background|cite|data)\s*=\s*(javascript|vbscript|data\s*:\s*text\/html)[^\s>]+/i',
            '',
            $html
        );

        $html = preg_replace('/\s+(?:style|dir|id|class|data-[a-z0-9-]+|on[a-z]+)="[^"]*"/i', '', $html);
        $html = preg_replace("/\s+(?:style|dir|id|class|data-[a-z0-9-]+|on[a-z]+)='[^']*'/i", '', $html);
        // Event handler tanpa quote: <a onclick=alert(1)>.
        $html = preg_replace('/\s+on[a-z]+\s*=\s*[^\s>]+/i', '', $html);

        for ($i = 0; $i < 3; $i++) {
            $html = preg_replace('/<(p|div|span|b|strong|h1|h2|h3|h4|ul|ol)[^>]*>\s*(?:<br\s*\/?>\s*)*<\/\1>/i', '', $html);
        }
        $html = preg_replace('/(<br\s*\/?>\s*){2,}/i', '<br>', $html);

        return trim($html);
    }
}

if (! function_exists('safeSvg')) {
    /**
     * Kembalikan markup SVG yang aman untuk dirender mentah,
     * atau string kosong bila input bukan ikon SVG yang valid.
     * Hanya bentuk vektor dasar yang dipertahankan; <script>,
     * event handler, referensi eksternal, dan gaya inline dibuang.
     */
    function safeSvg(?string $svg): string
    {
        if (! $svg || trim($svg) === '') {
            return '';
        }
        $svg = trim($svg);
        if (! preg_match('/\A<svg\b[^>]*>.*<\/svg\s*>\z/is', $svg)) {
            return '';
        }
        // Buang elemen berbahaya beserta seluruh isinya
        // (foreignObject bisa menyisipkan HTML, use/image memuat eksternal).
        $svg = preg_replace(
            '#<(script|foreignobject|image|use|iframe|object|embed|form|audio|video|style|link|meta|animate|animatetransform|animatemotion|set|mpath|cursor|font|text|textpath|tspan|a|switch)[^>]*>.*?</\1\s*>#is',
            '',
            $svg
        );
        // Sisa elemen berbahaya yang self-closing / tak berpasangan.
        $svg = preg_replace(
            '#<(script|foreignobject|image|use|iframe|object|embed|form|audio|video|style|link|meta|animate|animatetransform|animatemotion|set|mpath|cursor|font|text|textpath|tspan|a|switch)\b[^>]*/?>#i',
            '',
            $svg
        );
        // Event handler (quoted maupun tidak) + gaya inline.
        $svg = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg);
        $svg = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg);
        // Protokol berbahaya pada atribut URL.
        $svg = preg_replace('/\s+(href|src|xlink:href)\s*=\s*(["\'])\s*(javascript|vbscript|data\s*:\s*text\/html).*?\2/i', '', $svg);
        $svg = preg_replace('/\s+(href|src|xlink:href)\s*=\s*(javascript|vbscript|data\s*:\s*text\/html)[^\s>]+/i', '', $svg);
        // Hanya bentuk vektor dasar yang dipertahankan.
        $svg = strip_tags($svg, '<svg><g><circle><rect><line><path><polyline><polygon><ellipse><title><desc>');
        $svg = trim($svg);
        if (! preg_match('/\A<svg\b[^>]*>.*<\/svg\s*>\z/is', $svg)) {
            return '';
        }

        return $svg;
    }
}

if (! function_exists('safeIcon')) {
    /**
     * Kembalikan markup ikon yang aman untuk dirender mentah,
     * atau string kosong bila input tidak valid.
     * Menerima dua format: SVG vektor (lihat safeSvg) dan ikon font
     * Bootstrap Icons strictly berbentuk <i class="bi bi-..."></i>.
     */
    function safeIcon(?string $icon): string
    {
        if (! $icon || trim($icon) === '') {
            return '';
        }
        $icon = trim($icon);
        $svg = safeSvg($icon);
        if ($svg !== '') {
            return $svg;
        }
        if (preg_match('#\A<i\s+class="bi(\s+bi-[a-z0-9-]+)?"\s*></i>\z#i', $icon)) {
            return $icon;
        }

        return '';
    }
}

if (! function_exists('techBrandColor')) {
    /**
     * Warna brand untuk ikon/teks teknologi agar tampil ala mockup
     * (HTML oranye, JS kuning, React cyan, dst).
     * Kembalikan null bila tidak dikenal — pemanggil memakai warna default.
     */
    function techBrandColor(?string $title): ?string
    {
        $map = [
            'html' => '#e34f26',
            'css' => '#3b9ded',
            'javascript' => '#f7df1e',
            'js' => '#f7df1e',
            'typescript' => '#3178c6',
            'ts' => '#3178c6',
            'react' => '#61dafb',
            'reactjs' => '#61dafb',
            'next' => '#ffffff',
            'node' => '#3c873a',
            'nodejs' => '#3c873a',
            'laravel' => '#ff2d20',
            'php' => '#8993be',
            'vue' => '#42b883',
            'tailwind' => '#38bdf8',
            'bootstrap' => '#7952b3',
            'mysql' => '#12a8ce',
            'postgres' => '#4a90c4',
            'postgresql' => '#4a90c4',
            'python' => '#ffd845',
            'git' => '#f05033',
            'github' => '#ffffff',
            'figma' => '#f24e1e',
            'docker' => '#2496ed',
            'wordpress' => '#21759b',
            'seo' => '#4285f4',
        ];

        $key = strtolower(trim((string) $title));
        foreach ($map as $needle => $color) {
            if (str_contains($key, $needle)) {
                return $color;
            }
        }

        return null;
    }
}

if (! function_exists('techIcon')) {
    /**
     * Markup ikon logo brand teknologi (Devicon) berdasarkan judul skill.
     * Bila teknologi tidak dikenal, kembalikan fallback (ikon dari database admin).
     */
    function techIcon(?string $title, string $fallback = ''): string
    {
        $map = [
            'github' => 'devicon-github-original',
            'visual studio' => 'devicon-vscode-plain',
            'vs code' => 'devicon-vscode-plain',
            'vscode' => 'devicon-vscode-plain',
            'html' => 'devicon-html5-plain',
            'css' => 'devicon-css3-plain',
            'javascript' => 'devicon-javascript-plain',
            'js' => 'devicon-javascript-plain',
            'typescript' => 'devicon-typescript-plain',
            'bootstrap' => 'devicon-bootstrap-plain',
            'php' => 'devicon-php-plain',
            'laravel' => 'devicon-laravel-plain',
            'codeigniter' => 'devicon-codeigniter-plain',
            'mysql' => 'devicon-mysql-plain',
            'postgresql' => 'devicon-postgresql-plain',
            'postgres' => 'devicon-postgresql-plain',
            'node' => 'devicon-nodejs-plain',
            'react' => 'devicon-react-original',
            'vue' => 'devicon-vuejs-plain',
            'tailwind' => 'devicon-tailwindcss-plain',
            'git' => 'devicon-git-plain',
            'python' => 'devicon-python-plain',
            'wordpress' => 'devicon-wordpress-plain',
            'figma' => 'devicon-figma-plain',
            'docker' => 'devicon-docker-plain',
            'next' => 'devicon-nextjs-plain',
        ];

        $key = strtolower(trim((string) $title));
        foreach ($map as $needle => $icon) {
            if (str_contains($key, $needle)) {
                return '<i class="'.$icon.' colored" aria-hidden="true"></i>';
            }
        }

        return $fallback;
    }
}

if (! function_exists('isEmptyRichText')) {
    /**
     * Cek apakah HTML dari editor rich text secara visual kosong.
     * Summernote sering mengirim '<p><br></p>' saat editor dibiarkan kosong,
     * yang tetap lolos validasi 'required|string' biasa.
     */
    function isEmptyRichText(?string $html): bool
    {
        if ($html === null || trim($html) === '') {
            return true;
        }

        $text = preg_replace('/&nbsp;|&#160;|&zwnj;/i', ' ', $html);
        if (trim(strip_tags((string) $text)) !== '') {
            return false;
        }

        // Tanpa teks, tapi konten visual (gambar/embed/tabel/hr) tetap dianggap terisi.
        return ! preg_match('/<(img|iframe|video|table|hr)\b/i', $html);
    }
}

if (! function_exists('optimizeImage')) {
    function optimizeImage(?string $path, int $maxDim = 1600, int $quality = 82): void
    {
        if (! $path) {
            return;
        }
        $fullPath = public_path(ltrim($path, '/'));
        if (! is_file($fullPath)) {
            return;
        }
        $info = @getimagesize($fullPath);
        if (! $info) {
            return;
        }
        [$w, $h] = [$info[0], $info[1]];
        $mime = $info['mime'] ?? '';

        if ($w <= $maxDim && filesize($fullPath) <= 220 * 1024) {
            return;
        }

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($fullPath),
            'image/png' => @imagecreatefrompng($fullPath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : false,
            default => false,
        };
        if (! $src) {
            return;
        }

        $scale = min(1, $maxDim / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = $src;
        if ($scale < 1) {
            $dst = imagecreatetruecolor($nw, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        }

        if ($mime === 'image/jpeg') {
            imagejpeg($dst, $fullPath, $quality);
        } elseif ($mime === 'image/png') {
            imagepng($dst, $fullPath, 6);
        } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
            imagewebp($dst, $fullPath, $quality);
        }

        imagedestroy($src);
        if ($dst !== $src) {
            imagedestroy($dst);
        }
    }
}

if (! function_exists('personJsonLd')) {
    function personJsonLd(): string
    {
        $sameAs = array_values(array_filter([
            setting('github_url', ''),
            setting('social_linkedin', ''),
            setting('social_instagram', ''),
            setting('social_facebook', ''),
            setting('social_youtube', ''),
        ]));
        $person = [
            '@type' => 'Person',
            '@id' => url('/').'#person',
            'name' => setting('full_name', 'Heri Agus Prasetyo'),
            'url' => url('/'),
            'image' => setting('profile_photo') ? url(setting('profile_photo')) : url('/og-image.png'),
            'jobTitle' => setting('role', 'Web Developer & Software Engineer'),
            'description' => setting('bio', 'Web Developer dan Software Engineer dengan latar belakang Sistem Informasi.'),
            'email' => setting('footer_email') ?: null,
            'telephone' => setting('whatsapp_number') ? '+'.setting('whatsapp_number') : null,
            'sameAs' => $sameAs,
            'alumniOf' => [
                '@type' => 'CollegeOrUniversity',
                'name' => setting('university', 'Universitas Mercu Buana Yogyakarta'),
            ],
            'knowsAbout' => ['Web Development', 'Software Engineering', 'Information Systems', 'PHP', 'Laravel', 'MySQL'],
        ];
        $website = [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => setting('site_name', 'Heri Agus Prasetyo'),
            'description' => setting('site_description', 'Portfolio pribadi Heri Agus Prasetyo.'),
            'inLanguage' => 'id-ID',
            'author' => ['@id' => url('/').'#person'],
        ];

        return (string) json_encode(
            ['@context' => 'https://schema.org', '@graph' => [$person, $website]],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
