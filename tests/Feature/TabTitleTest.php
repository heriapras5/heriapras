<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TabTitleTest extends TestCase
{
    use RefreshDatabase;

    private function setTab(string $key, string $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'general', 'type' => 'text']);
    }

    public function test_homepage_uses_tab_home_setting(): void
    {
        $this->setTab('tab_home', 'Judul Tab Custom');

        $this->get('/')->assertSee('<title>Judul Tab Custom</title>', false);
    }

    public function test_homepage_falls_back_to_short_brand(): void
    {
        $this->get('/')->assertSee('<title>Heri A Pras - Web Developer &amp; Software Engineer</title>', false);
    }

    public function test_portfolio_index_uses_tab_portfolio(): void
    {
        $this->setTab('tab_portfolio', 'Semua Karya - Heri A Pras');

        $this->get('/portfolio')->assertSee('<title>Semua Karya - Heri A Pras</title>', false);
    }

    public function test_detail_fills_project_pattern(): void
    {
        $this->setTab('tab_project', '{project} - Heri A Pras');
        Project::create([
            'name' => 'Proyek Judul',
            'slug' => 'proyek-judul',
            'description' => '<p>Deskripsi.</p>',
            'status' => true,
        ]);

        $this->get(route('projects.detail', 'proyek-judul'))
            ->assertSee('<title>Proyek Judul - Heri A Pras</title>', false);
    }

    public function test_unknown_placeholder_is_left_as_is(): void
    {
        $this->assertSame('A {tidak-ada} B', fillTitle('A {tidak-ada} B', ['project' => 'X']));
    }
}
