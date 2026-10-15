<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Start',
                'slug' => 'paket-start',
                'price' => 'Rp1.249.000',
                'old_price' => 'Rp1.499.000',
                'tagline' => 'Cocok untuk bisnis yang baru mulai online.',
                'facilities' => [
                    'Domain .COM - 1 tahun',
                    'Hosting - 1 tahun',
                    'SSL / HTTPS',
                    '1-5 halaman website',
                    'Profil bisnis',
                    'Katalog hingga 20 produk',
                    'Galeri foto',
                    'Tombol WhatsApp',
                    'Google Maps',
                    'Mobile Friendly',
                    'SEO dasar',
                    'Bisa edit produk sendiri',
                ],
                'renewal' => 'Rp1.000.000/tahun',
                'badge' => null,
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Paket Business',
                'slug' => 'paket-business',
                'price' => 'Rp2.499.000',
                'tagline' => 'Cocok untuk UMKM dan bisnis yang ingin tampil lebih profesional.',
                'facilities' => [
                    'Domain .COM - 1 tahun',
                    'Hosting - 1 tahun',
                    'SSL / HTTPS',
                    '1-8 halaman website',
                    'Profil bisnis',
                    'Katalog hingga 50 produk',
                    'Galeri foto',
                    'Tombol WhatsApp',
                    'Google Maps',
                    'Mobile Friendly',
                    'SEO dasar',
                    'Bisa edit produk sendiri',
                    'Email bisnis',
                    'Google Search Console',
                ],
                'renewal' => 'Rp1.250.000/tahun',
                'badge' => 'Terlaris',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Paket Pro',
                'slug' => 'paket-pro',
                'price' => 'Rp3.999.000',
                'tagline' => 'Cocok untuk perusahaan dengan kebutuhan website yang lebih lengkap.',
                'facilities' => [
                    'Domain .COM - 1 tahun',
                    'Hosting - 1 tahun',
                    'SSL / HTTPS',
                    '1-15 halaman website',
                    'Profil perusahaan',
                    'Katalog hingga 100 produk',
                    'Galeri foto',
                    'Tombol WhatsApp',
                    'Google Maps',
                    'Mobile Friendly',
                    'SEO dasar',
                    'Bisa edit produk sendiri',
                    'Email bisnis',
                    'Google Search Console',
                    'Backup & maintenance dasar',
                ],
                'renewal' => 'Rp1.500.000/tahun',
                'badge' => null,
                'sort_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Paket Custom',
                'slug' => 'paket-custom',
                'price' => 'Mulai Rp5.000.000',
                'tagline' => 'Untuk kebutuhan website dan sistem yang lebih spesifik.',
                'facilities' => [
                    'Desain custom',
                    'Fitur custom sesuai kebutuhan',
                    'Katalog produk custom',
                    'Sistem admin',
                    'Integrasi API',
                    'Payment Gateway',
                    'Booking / Reservasi',
                    'Member / Login',
                    'Database & sistem khusus',
                    'Dan kebutuhan lainnya',
                ],
                'renewal' => 'Sesuai kebutuhan website',
                'badge' => null,
                'sort_order' => 4,
                'status' => true,
            ],
        ];

        foreach ($packages as $i => $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package + ['sort_order' => $package['sort_order'] ?? $i + 1, 'status' => $package['status'] ?? true]
            );
        }
    }
}
