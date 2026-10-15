<?php

namespace Database\Seeders;

use App\Models\TimelineItem;
use Illuminate\Database\Seeder;

class TimelineItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['type' => 'education', 'title' => 'S1 Sistem Informasi', 'organization' => 'Universitas Mercu Buana Yogyakarta', 'period' => 'Lulus 2023 - IPK 3.34', 'description' => 'Mempelajari sistem informasi, pengembangan aplikasi, database, analisis sistem, dan teknologi informasi.', 'sort_order' => 1],
            ['type' => 'education', 'title' => 'SMK Negeri 01 Bangsri (RPL)', 'organization' => 'Rekayasa Perangkat Lunak', 'period' => 'Lulus 2016', 'description' => 'Dasar pemrograman, pengembangan web, dan rekayasa perangkat lunak.', 'sort_order' => 2],
            ['type' => 'experience', 'title' => 'Web Developer (Berbasis Project)', 'organization' => 'Freelance', 'period' => 'Berbasis Project', 'description' => 'Membangun website company profile, katalog produk, dan sistem manajemen seperti SMOPF serta aplikasi service HP Juneo Phone.', 'sort_order' => 3],
            ['type' => 'experience', 'title' => 'Kerja Praktek', 'organization' => 'Fakultas Teknologi Informasi UMBY', 'period' => '2020 (6 bulan)', 'description' => 'Mendukung kegiatan teknologi informasi di lingkungan fakultas.', 'sort_order' => 4],
            ['type' => 'experience', 'title' => 'Barista', 'organization' => 'Zhoklat Indonesia', 'period' => '2021-2022', 'description' => 'Menyajikan olahan minuman coklat, teh, dan kopi. Melatih komunikasi dan kerja tim.', 'sort_order' => 5],
            ['type' => 'experience', 'title' => 'Magang', 'organization' => 'Royyan Computer Mlonggo', 'period' => '2015 (3 bulan)', 'description' => 'Membantu layanan komputer dan pengenalan dunia kerja.', 'sort_order' => 6],
        ];

        TimelineItem::query()->delete();

        foreach ($items as $item) {
            TimelineItem::create($item + ['is_active' => true]);
        }
    }
}
