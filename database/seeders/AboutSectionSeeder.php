<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        AboutSection::updateOrCreate(
            ['is_active' => true],
            [
                'title' => 'Building digital solutions with technology.',
                'subtitle' => 'About Heri',
                'description' => 'Saya adalah Heri Agus Prasetyo, Web Developer dan Software Engineer dengan latar belakang Sistem Informasi dari Universitas Mercu Buana Yogyakarta. Fokus saya adalah membangun website, aplikasi berbasis web, dan sistem informasi yang modern, responsif, terstruktur, serta mudah digunakan.',
                'vision' => 'Mengubah kebutuhan bisnis dan organisasi menjadi produk digital yang berfungsi dengan baik.',
                'mission' => [
                    'Membangun website yang cepat, responsif, dan mudah digunakan.',
                    'Mengembangkan sistem informasi yang membantu proses kerja menjadi lebih terstruktur.',
                    'Terus belajar mengikuti perkembangan teknologi dan praktik engenharia perangkat lunak.',
                    'Mengubah ide menjadi produk digital yang nyata dan bernilai.',
                ],
                'image' => '',
                'is_active' => true,
            ]
        );

        $this->command->info('About section seeded successfully.');
    }
}
