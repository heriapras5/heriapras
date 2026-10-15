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
            'study_program' => ['value' => 'Program Studi Sistem Informasi', 'group' => 'profile', 'type' => 'text'],
            'university' => ['value' => 'Universitas Mercu Buana Yogyakarta', 'group' => 'profile', 'type' => 'text'],
            'location' => ['value' => 'Yogyakarta, Indonesia', 'group' => 'profile', 'type' => 'text'],
            'bio' => ['value' => 'Saya membangun website modern dan solusi digital yang mengubah ide menjadi produk nyata.', 'group' => 'profile', 'type' => 'textarea'],
            'cv_url' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'portfolio_url' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'profile_photo' => ['value' => '', 'group' => 'profile', 'type' => 'text'],
            'logo_image' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'favicon' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'hero_background' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'whatsapp_number' => ['value' => '6285156920786', 'group' => 'general', 'type' => 'text'],
            'footer_address' => ['value' => 'Yogyakarta, Indonesia', 'group' => 'general', 'type' => 'text'],
            'footer_email' => ['value' => 'heriapras5@gmail.com', 'group' => 'general', 'type' => 'text'],
            'maps_embed' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'social_facebook' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'social_instagram' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'social_linkedin' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'social_youtube' => ['value' => '', 'group' => 'general', 'type' => 'text'],
            'github_url' => ['value' => 'https://github.com/heriapras5', 'group' => 'general', 'type' => 'text'],
            'hero_typed_roles' => ['value' => 'Web Developer, Software Engineer', 'group' => 'landing', 'type' => 'text'],
            'landing_about_title' => ['value' => 'Tentang', 'group' => 'landing', 'type' => 'text'],
            'landing_about_subtitle' => ['value' => 'Mengenal latar belakang, pendidikan, dan fokus saya dalam membangun produk digital.', 'group' => 'landing', 'type' => 'text'],
            'landing_skills_title' => ['value' => 'Keahlian', 'group' => 'landing', 'type' => 'text'],
            'landing_skills_subtitle' => ['value' => 'Teknologi yang saya gunakan untuk mewujudkan ide menjadi produk nyata.', 'group' => 'landing', 'type' => 'text'],
            'landing_resume_title' => ['value' => 'Perjalanan', 'group' => 'landing', 'type' => 'text'],
            'landing_resume_subtitle' => ['value' => 'Dari bangku pendidikan hingga membangun produk digital yang dipakai untuk kebutuhan nyata.', 'group' => 'landing', 'type' => 'text'],
            'landing_portfolio_title' => ['value' => 'Portfolio', 'group' => 'landing', 'type' => 'text'],
            'landing_portfolio_subtitle' => ['value' => 'Berikut adalah beberapa proyek website dan aplikasi yang telah saya bangun.', 'group' => 'landing', 'type' => 'text'],
            'landing_services_title' => ['value' => 'Layanan', 'group' => 'landing', 'type' => 'text'],
            'landing_services_subtitle' => ['value' => 'Jenis produk digital yang bisa saya bantu bangun untuk bisnis dan organisasi.', 'group' => 'landing', 'type' => 'text'],
            'landing_testimonials_title' => ['value' => 'Testimoni', 'group' => 'landing', 'type' => 'text'],
            'landing_testimonials_subtitle' => ['value' => 'Catatan dari klien yang pernah bekerja sama.', 'group' => 'landing', 'type' => 'text'],
            'landing_contact_title' => ['value' => 'Kontak', 'group' => 'landing', 'type' => 'text'],
            'landing_contact_subtitle' => ['value' => 'Punya ide atau project? Mari kita diskusikan bersama.', 'group' => 'landing', 'type' => 'text'],
        ];

        foreach ($defaults as $key => $data) {
            Setting::updateOrCreate(['key' => $key], $data);
        }
    }
}
