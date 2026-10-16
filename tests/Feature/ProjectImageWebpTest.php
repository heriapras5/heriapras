<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectImageWebpTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_cover_and_gallery_are_converted_to_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP tidak tersedia.');
        }
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Storage::fake('public');

        $response = $this->post(route('admin.projects.store'), [
            'name' => 'Proyek WebP',
            'description' => '<p>Deskripsi proyek untuk uji konversi WebP.</p>',
            'status' => '1',
            'main_image' => UploadedFile::fake()->image('cover.jpg', 1200, 800),
            'gallery_images' => [UploadedFile::fake()->image('galeri.png', 800, 600)],
        ]);

        $response->assertRedirect(route('admin.projects.index'));

        $images = Project::where('name', 'Proyek WebP')->firstOrFail()->images;
        $this->assertNotEmpty($images, 'project must have stored images');

        foreach ($images as $image) {
            $this->assertStringEndsWith('.webp', $image->image, 'project image must be converted to WebP');
            $rel = (string) preg_replace('#^storage/#', '', $image->image);
            Storage::disk('public')->assertExists($rel);
            $info = @getimagesizefromstring((string) Storage::disk('public')->get($rel));
            $this->assertSame('image/webp', $info['mime'] ?? null);
        }
    }
}
