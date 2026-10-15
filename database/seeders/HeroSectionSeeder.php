<?php

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    public function run(): void
    {
        HeroSection::updateOrCreate(
            ['title' => 'Web Developer & Software Engineer'],
            [
                'subtitle' => 'Saya membangun website modern dan solusi digital yang mengubah ide menjadi produk nyata.',
                'description' => null,
                'button_text' => 'View My Projects',
                'button_url' => '#projects',
                'image' => null,
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        $this->command->info('Hero section seeded successfully.');
    }
}
