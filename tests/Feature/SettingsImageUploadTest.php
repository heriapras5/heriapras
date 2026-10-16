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
}
