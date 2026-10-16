<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => ['value' => 'Heri Agus Prasetyo', 'group' => 'general', 'type' => 'text'],
            'site_tagline' => ['value' => 'WEB DEVELOPER & SOFTWARE ENGINEER', 'group' => 'general', 'type' => 'text'],
            'site_description' => ['value' => 'Portfolio pribadi Heri Agus Prasetyo, Web Developer dan Software Engineer dengan latar belakang Sistem Informasi Universitas Mercu Buana Yogyakarta.', 'group' => 'general', 'type' => 'textarea'],
            'full_name' => ['value' => 'Heri Agus Prasetyo', 'group' => 'profile', 'type' => 'text'],
            'role' => ['value' => 'Web Developer & Software Engineer', 'group' => 'profile', 'type' => 'text'],
            'location' => ['value' => 'Yogyakarta, Indonesia', 'group' => 'profile', 'type' => 'text'],
            'bio' => ['value' => 'Nama saya Heri Agus Prasetyo, lulusan Universitas Mercu Buana Yogyakarta, Program Studi Sistem Informasi. Saya adalah seorang Web Developer yang berpengalaman membangun dan mengembangkan berbagai proyek website dan aplikasi berbasis web. Sejak tahun 2025, saya aktif mengerjakan berbagai proyek freelance dengan fokus pada pengembangan solusi digital sesuai kebutuhan klien.', 'group' => 'profile', 'type' => 'textarea'],
            'cv_url' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'portfolio_url' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'profile_photo' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'logo_image' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'favicon' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'whatsapp_number' => ['value' => '6285156920786', 'group' => 'general', 'type' => 'text'],
            'footer_email' => ['value' => 'heriapras5@gmail.com', 'group' => 'general', 'type' => 'text'],
            'github_url' => ['value' => 'https://github.com/heriapras5', 'group' => 'general', 'type' => 'text'],
            'hero_typed_roles' => ['value' => 'Web Developer, Software Engineer', 'group' => 'landing', 'type' => 'text'],
            'landing_portfolio_title' => ['value' => 'Portfolio', 'group' => 'landing', 'type' => 'text'],
            'landing_portfolio_subtitle' => ['value' => 'Berikut adalah beberapa proyek website dan aplikasi yang telah saya bangun.', 'group' => 'landing', 'type' => 'text'],
        ];

        foreach ($defaults as $key => $data) {
            Setting::updateOrCreate(['key' => $key], $data);
        }
    }
}
