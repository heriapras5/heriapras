<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function admin()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user);

        return $user;
    }

    public function test_profile_photo_upload_persists_and_serves(): void
    {
        $this->admin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('foto.jpg', 400, 400);

        $response = $this->put(route('admin.settings.update'), [
            'profile_photo' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        $stored = \App\Models\Setting::where('key', 'profile_photo')->value('value');
        $this->assertNotEmpty($stored, 'profile_photo setting must be saved');
        Storage::disk('public')->assertExists(str_replace('storage/', '', $stored));
    }

    public function test_logo_image_upload_persists(): void
    {
        $this->admin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('logo.png', 200, 200);

        $response = $this->put(route('admin.settings.update'), [
            'logo_image' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        $stored = \App\Models\Setting::where('key', 'logo_image')->value('value');
        $this->assertNotEmpty($stored, 'logo_image setting must be saved');
    }

    public function test_jpg_upload_is_converted_to_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP tidak tersedia.');
        }
        $this->admin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('foto.jpg', 1200, 800);

        $response = $this->put(route('admin.settings.update'), [
            'profile_photo' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        $stored = (string) \App\Models\Setting::where('key', 'profile_photo')->value('value');
        $this->assertStringEndsWith('.webp', $stored, 'JPEG upload must be converted to WebP');
        $rel = (string) preg_replace('#^storage/#', '', $stored);
        Storage::disk('public')->assertExists($rel);
        $info = @getimagesizefromstring((string) Storage::disk('public')->get($rel));
        $this->assertSame('image/webp', $info['mime'] ?? null);
    }

    public function test_png_upload_is_converted_to_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP tidak tersedia.');
        }
        $this->admin();
        Storage::fake('public');

        $gd = imagecreatetruecolor(600, 400);
        imagefill($gd, 0, 0, imagecolorallocate($gd, 30, 90, 200));
        $tmp = tempnam(sys_get_temp_dir(), 'pngtest').'.png';
        imagepng($gd, $tmp);
        imagedestroy($gd);
        $file = new UploadedFile($tmp, 'foto.png', 'image/png', null, true);

        $response = $this->put(route('admin.settings.update'), [
            'profile_photo' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        $stored = (string) \App\Models\Setting::where('key', 'profile_photo')->value('value');
        $this->assertStringEndsWith('.webp', $stored, 'PNG upload must be converted to WebP');
        $this->assertStringContainsString('heri-profile', $stored, 'fixed profile filename must be kept');
        $rel = (string) preg_replace('#^storage/#', '', $stored);
        Storage::disk('public')->assertExists($rel);
        $info = @getimagesizefromstring((string) Storage::disk('public')->get($rel));
        $this->assertSame('image/webp', $info['mime'] ?? null);
    }
}
