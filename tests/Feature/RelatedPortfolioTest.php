<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatedPortfolioTest extends TestCase
{
    use RefreshDatabase;

    private function makeProject(string $name, string $slug): Project
    {
        return Project::create([
            'name' => $name,
            'slug' => $slug,
            'description' => '<p>Deskripsi '.$name.'.</p>',
            'status' => true,
        ]);
    }

    public function test_detail_shows_related_portfolio_from_same_category(): void
    {
        $category = ProjectCategory::create(['name' => 'Company Profile', 'slug' => 'company-profile', 'status' => true]);
        $main = $this->makeProject('Proyek Utama', 'proyek-utama');
        $main->categories()->attach($category->id);
        $other = $this->makeProject('Proyek Serupa', 'proyek-serupa');
        $other->categories()->attach($category->id);

        $response = $this->get(route('projects.detail', 'proyek-utama'));

        $response->assertOk();
        $response->assertSee('Portofolio Terkait');
        $response->assertSee('Proyek Serupa');
    }

    public function test_detail_hides_related_section_when_none(): void
    {
        $this->makeProject('Proyek Tunggal', 'proyek-tunggal');

        $response = $this->get(route('projects.detail', 'proyek-tunggal'));

        $response->assertOk();
        $response->assertDontSee('Portofolio Terkait');
    }
}
