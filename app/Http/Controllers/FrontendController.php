<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\TimelineItem;
use App\Services\Frontend\FrontendService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __construct(
        protected FrontendService $frontendService,
    ) {}

    public function home()
    {
        $aboutSection = AboutSection::where('is_active', true)->first();
        $projects = $this->frontendService->getProjects();
        $categories = $this->frontendService->getProjectCategories();
        $allServices = $this->frontendService->getServices();
        $skills = $allServices->filter(fn ($service) => $service->type !== 'service');
        $timeline = TimelineItem::where('is_active', true)->orderBy('sort_order')->get();
        $educationItems = $timeline->where('type', 'education');
        $experienceItems = $timeline->where('type', '!=', 'education');

        // Kumpulkan kategori dari relasi pivot (sumber utama) + kolom legacy category_id.
        $usedCategoryIds = $projects->flatMap(fn ($project) => $project->categories->pluck('id'))
            ->merge($projects->pluck('category_id'))
            ->filter()->unique()->values();
        $portfolioCategories = $categories->whereIn('id', $usedCategoryIds)->values();

        $stats = [
            ['value' => $projects->count(), 'label' => 'Project', 'sub' => 'karya portofolio', 'icon' => 'bi-journal-code'],
            ['value' => $portfolioCategories->count(), 'label' => 'Kategori', 'sub' => 'jenis project', 'icon' => 'bi-collection'],
            ['value' => $skills->count(), 'label' => 'Keahlian', 'sub' => 'teknologi dikuasai', 'icon' => 'bi-lightbulb'],
        ];
        $stats = array_values(array_filter($stats, fn ($stat) => $stat['value'] > 0));

        return view('frontend.home', compact(
            'aboutSection',
            'projects',
            'portfolioCategories',
            'skills',
            'educationItems',
            'experienceItems',
            'stats'
        ));
    }

    public function portfolioDetail($slug)
    {
        $project = $this->frontendService->getProjectBySlug($slug);
        if (! $project) {
            abort(404);
        }
        $prevProject = $this->frontendService->getPrevProject($project->id);
        $nextProject = $this->frontendService->getNextProject($project->id);

        return view('frontend.detail-project', compact('project', 'prevProject', 'nextProject'));
    }

    public function favicon()
    {
        $size = 64;
        // Prioritas: foto hero (profile_photo) agar icon tab browser sama dengan foto hero,
        // lalu favicon upload admin sebagai cadangan, lalu ikon default.
        $logo = setting('profile_photo') ?: setting('favicon') ?: '';

        $img = imagecreatetruecolor($size, $size);
        imagesavealpha($img, true);
        imagealphablending($img, false);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefilledrectangle($img, 0, 0, $size, $size, $transparent);

        $source = null;
        if ($logo) {
            $fullPath = $this->resolveAssetPath($logo);
            if ($fullPath && is_file($fullPath)) {
                $source = @imagecreatefromstring((string) file_get_contents($fullPath));
            }
        }

        imagealphablending($img, true);

        if ($source) {
            $sw = imagesx($source);
            $sh = imagesy($source);
            $scale = max($size / $sw, $size / $sh);
            $nw = (int) round($sw * $scale);
            $nh = (int) round($sh * $scale);
            $tmp = imagecreatetruecolor($nw, $nh);
            imagesavealpha($tmp, true);
            imagealphablending($tmp, true);
            imagecopyresampled($tmp, $source, 0, 0, 0, 0, $nw, $nh, $sw, $sh);
            $sx = (int) (($nw - $size) / 2);
            $sy = (int) (($nh - $size) / 2);
            imagecopy($img, $tmp, $sx, $sy, 0, 0, $size, $size);
            imagedestroy($tmp);
            imagedestroy($source);
        } else {
            // Default: ikon earth. Tetap dinamis — favicon/logo upload admin dipakai bila ada.
            $earth = public_path('assets/img/favicon-earth.png');
            if (is_file($earth)) {
                return response()->file($earth, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=31536000, immutable',
                ]);
            }
            $blue = imagecolorallocate($img, 37, 99, 235);
            $white = imagecolorallocate($img, 255, 255, 255);
            imagefilledellipse($img, $size / 2, $size / 2, $size, $size, $blue);
            imagefilledrectangle($img, 11, 13, 53, 38, $white);
            imagefilledrectangle($img, 22, 41, 42, 45, $white);
            imagefilledrectangle($img, 28, 45, 36, 49, $white);
        }

        imagealphablending($img, false);
        $cx = $cy = ($size - 1) / 2;
        $r = $size / 2;
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                if ((($x - $cx) * ($x - $cx)) + (($y - $cy) * ($y - $cy)) > ($r * $r)) {
                    imagesetpixel($img, $x, $y, $transparent);
                }
            }
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function ogImage()
    {
        $width = 1200;
        $height = 630;
        $diameter = 560;

        $logo = setting('seo_og_image') ?: setting('logo_image') ?: setting('profile_photo');

        $img = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);

        $source = null;
        if ($logo) {
            $fullPath = $this->resolveAssetPath($logo);
            if ($fullPath && is_file($fullPath)) {
                $source = @imagecreatefromstring((string) file_get_contents($fullPath));
            }
        }

        if ($source) {
            $sw = imagesx($source);
            $sh = imagesy($source);
            $side = min($sw, $sh);
            $sx = (int) (($sw - $side) / 2);
            $sy = (int) (($sh - $side) / 2);
            $square = imagecreatetruecolor($diameter, $diameter);
            imagesavealpha($square, true);
            imagealphablending($square, true);
            imagecopyresampled($square, $source, 0, 0, $sx, $sy, $diameter, $diameter, $side, $side);
            imagedestroy($source);

            $c = ($diameter - 1) / 2;
            $r = $diameter / 2;
            for ($y = 0; $y < $diameter; $y++) {
                for ($x = 0; $x < $diameter; $x++) {
                    if ((($x - $c) * ($x - $c)) + (($y - $c) * ($y - $c)) > ($r * $r)) {
                        imagesetpixel($square, $x, $y, $white);
                    }
                }
            }

            imagecopy($img, $square, (int) (($width - $diameter) / 2), (int) (($height - $diameter) / 2), 0, 0, $diameter, $diameter);
            imagedestroy($square);
        } else {
            $blue = imagecolorallocate($img, 37, 99, 235);
            $cx = $width / 2;
            $cy = $height / 2;
            imagefilledellipse($img, $cx, $cy, $diameter, $diameter, $blue);
            imagefilledrectangle($img, (int) ($cx - 150), (int) ($cy - 110), (int) ($cx + 150), (int) ($cy - 20), $white);
            imagefilledrectangle($img, (int) ($cx - 90), (int) ($cy + 10), (int) ($cx + 90), (int) ($cy + 40), $white);
            imagefilledrectangle($img, (int) ($cx - 45), (int) ($cy + 55), (int) ($cx + 45), (int) ($cy + 85), $white);
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        $this->frontendService->submitContact($validated);

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih sudah menghubungi saya.');
    }

    protected function resolveAssetPath(string $path): ?string
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $relative = substr($path, strlen('storage/'));

            return storage_path('app/public/'.$relative);
        }

        $publicPath = public_path($path);
        if (is_file($publicPath)) {
            return $publicPath;
        }

        return storage_path('app/public/'.$path);
    }
}
