<?php

namespace Tests\Feature;

use App\Models\AboutSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CvTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_id_download_returns_pdf(): void
    {
        $response = $this->get('/cv');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_cv_en_download_returns_pdf(): void
    {
        $response = $this->get('/cv/en');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_cv_preview_renders_ats_friendly_html(): void
    {
        $html = $this->get('/cv/preview')->assertOk()->getContent();

        $this->assertStringContainsString('Heri Agus Prasetyo', $html);
        $this->assertStringContainsString('Ringkasan', $html);
        $this->assertStringContainsString('Pengalaman Kerja', $html);
        $this->assertStringContainsString('Pendidikan', $html);
    }

    public function test_cv_en_preview_renders_english_sections(): void
    {
        $html = $this->get('/cv/en/preview')->assertOk()->getContent();

        $this->assertStringContainsString('Summary', $html);
        $this->assertStringContainsString('Work Experience', $html);
        $this->assertStringContainsString('Selected Projects', $html);
    }

    public function test_homepage_shows_cv_download_button(): void
    {
        AboutSection::create(['title' => 'Tentang', 'description' => 'Deskripsi.', 'is_active' => true]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Unduh CV', $html);
        $this->assertStringContainsString('/cv', $html);
    }

    public function test_cv_redirects_to_external_url_when_configured(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'cv_url'],
            ['value' => 'https://example.com/cv.pdf', 'group' => 'general', 'type' => 'text']
        );

        $this->get('/cv')->assertRedirect('https://example.com/cv.pdf');
    }
}
