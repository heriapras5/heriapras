<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'Berapa lama proses pembuatan website?', 'answer' => 'Lama pengerjaan tergantung kompleksitas website. Untuk website company profile standar membutuhkan waktu sekitar 1-2 minggu, sedangkan website dengan fitur khusus bisa memakan waktu 2-4 minggu. Kami selalu memberikan estimasi waktu yang jelas di awal.', 'sort_order' => 1],
            ['question' => 'Apakah website yang dibuat sudah termasuk domain dan hosting?', 'answer' => 'Ya, kami menyediakan paket lengkap termasuk domain dan hosting. Namun jika Anda sudah memiliki domain dan hosting sendiri, kami bisa membantu melakukan setup dan integrasi.', 'sort_order' => 2],
            ['question' => 'Apakah website saya akan responsive di hp?', 'answer' => 'Tentu! Semua website yang kami buat menggunakan pendekatan Mobile First, sehingga tampil sempurna di semua perangkat mulai dari smartphone, tablet, laptop, hingga desktop.', 'sort_order' => 3],
            ['question' => 'Apakah saya bisa mengelola konten website sendiri?', 'answer' => 'Ya, setiap website dilengkapi dengan CMS (Content Management System) yang mudah digunakan. Anda bisa mengupdate konten, menambah halaman, mengelola portfolio, dan lainnya tanpa perlu pengetahuan coding.', 'sort_order' => 4],
            ['question' => 'Apakah website yang dibuat sudah SEO friendly?', 'answer' => 'Ya, semua website kami optimasi untuk SEO. Mulai dari struktur kode yang bersih, meta tags, Open Graph, sitemap XML, hingga kecepatan loading yang dioptimasi untuk mendapat skor baik di Google PageSpeed.', 'sort_order' => 5],
            ['question' => 'Apakah ada garansi atau support setelah website selesai?', 'answer' => 'Ya, kami memberikan garansi dan support penuh. Setelah website launching, kami siap membantu jika ada masalah teknis, update konten, atau pertanyaan seputar pengelolaan website.', 'sort_order' => 6],
            ['question' => 'Bagaimana cara memesan jasa pembuatan website?', 'answer' => 'Anda bisa menghubungi kami via WhatsApp, email, atau formulir kontak di website ini. Konsultasi awal gratis, kami akan mendengarkan kebutuhan Anda dan memberikan solusi terbaik beserta estimasi harga.', 'sort_order' => 7],
            ['question' => 'Teknologi apa yang digunakan untuk membuat website?', 'answer' => 'Kami menggunakan teknologi modern seperti Laravel (PHP Framework), Tailwind CSS, Alpine.js, dan MySQL. Website yang dihasilkan cepat, aman, dan mudah dikembangkan untuk kebutuhan masa depan.', 'sort_order' => 8],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
