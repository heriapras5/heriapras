<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SyncPortfolioImagesTest extends TestCase
{
    use RefreshDatabase;

    private function pngBytes(): string
    {
        $gd = imagecreatetruecolor(640, 400);
        imagefill($gd, 0, 0, imagecolorallocate($gd, 40, 120, 200));
        ob_start();
        imagepng($gd);
        $bytes = (string) ob_get_clean();
        imagedestroy($gd);

        return $bytes;
    }

    private function detailPage(string ...$urls): string
    {
        $items = array_map(
            fn ($u) => ['src' => $u, 'alt' => 'Demo'],
            $urls
        );
        // Tiru format sumber: seluruh tanda kutip di-escape sebagai \u0022
        // (literal string JS, bukan JSON mentah).
        $escaped = str_replace('"', '\u0022', (string) json_encode($items));

        return '<div x-data="{ images: JSON.parse(\''.$escaped.'\') }"></div>';
    }

    public function test_sync_registers_cover_and_gallery_as_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP tidak tersedia.');
        }
        Storage::fake('public');

        $project = Project::create([
            'name' => 'Proyek Coba',
            'slug' => 'proyek-coba',
            'description' => '<p>Deskripsi.</p>',
            'status' => true,
        ]);
        Storage::disk('public')->put('projects-sync/lama.png', $this->pngBytes());
        $project->images()->create(['image' => 'storage/projects-sync/lama.png', 'type' => 'cover', 'sort_order' => 0]);

        $png = $this->pngBytes();
        Http::fake([
            'inoweb.my.id/portfolio/*' => Http::response($this->detailPage(
                'https://inoweb.my.id/storage/projects/cover123.png',
                'https://inoweb.my.id/storage/projects/gal1.png',
                'https://inoweb.my.id/storage/projects/gal2.jpg',
            ), 200),
            'inoweb.my.id/storage/*' => Http::response($png, 200, ['Content-Type' => 'image/png']),
        ]);

        $this->artisan('portfolio:sync-images', ['--source' => 'https://inoweb.my.id'])
            ->assertSuccessful();

        $images = $project->fresh()->images;
        $this->assertCount(3, $images);

        $cover = $images->firstWhere('type', 'cover');
        $this->assertNotNull($cover);
        $this->assertSame('storage/projects-sync/proyek-coba.webp', $cover->image);
        Storage::disk('public')->assertExists('projects-sync/proyek-coba.webp');

        $galleries = $images->where('type', 'gallery')->sortBy('sort_order')->values();
        $this->assertCount(2, $galleries);
        $this->assertSame('storage/projects-sync/proyek-coba-galeri-01.webp', $galleries[0]->image);
        $this->assertSame('storage/projects-sync/proyek-coba-galeri-02.webp', $galleries[1]->image);

        // Cover lama (file + baris) ikut tergantikan.
        Storage::disk('public')->assertMissing('projects-sync/lama.png');
        $this->assertSame(0, ProjectImage::where('image', 'storage/projects-sync/lama.png')->count());
    }

    public function test_failed_download_keeps_old_data_intact(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'name' => 'Proyek Gagal',
            'slug' => 'proyek-gagal',
            'description' => '<p>Deskripsi.</p>',
            'status' => true,
        ]);
        Storage::disk('public')->put('projects-sync/lama.png', $this->pngBytes());
        $project->images()->create(['image' => 'storage/projects-sync/lama.png', 'type' => 'cover', 'sort_order' => 0]);

        Http::fake([
            'inoweb.my.id/portfolio/*' => Http::response($this->detailPage(
                'https://inoweb.my.id/storage/projects/hilang.png',
            ), 200),
            'inoweb.my.id/storage/*' => Http::response('', 404),
        ]);

        $this->artisan('portfolio:sync-images', ['--source' => 'https://inoweb.my.id', '--slug' => 'proyek-gagal'])
            ->assertSuccessful();

        $this->assertSame(1, $project->fresh()->images()->count());
        $this->assertSame('storage/projects-sync/lama.png', $project->fresh()->images()->first()->image);
        Storage::disk('public')->assertExists('projects-sync/lama.png');
    }
}
