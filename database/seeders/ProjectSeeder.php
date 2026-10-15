<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

/**
 * Data portofolio project nyata yang pernah dibangun.
 * Urutan array = urutan tampil (dibuat dari belakang agar created_at desc sesuai).
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $techStack = ['Laravel 12', 'Tailwind CSS', 'Alpine.js', 'MySQL'];

        $projects = [
            [
                'name' => 'Website DUYUFUNA',
                'slug' => 'website-duyufuna',
                'category' => 'company',
                'client' => 'DUYUFUNA',
                'description' => '<p>Website ini dirancang sebagai company profile untuk menampilkan berbagai layanan pendukung perjalanan ibadah Umrah dan Haji di Tanah Suci, meliputi Makkah, Madinah, dan Jeddah.</p><p>Melalui website ini, calon pelanggan seperti jamaah, travel agency, maupun penyelenggara perjalanan ibadah dapat melihat berbagai pilihan layanan secara lengkap, memperoleh informasi harga dan detail layanan, hingga menghubungi tim DUYUFUNA secara langsung melalui WhatsApp.</p><p>Solusi ini cocok bagi brand penyedia layanan Umrah dan Haji yang ingin menghadirkan informasi layanan secara profesional, mudah diakses, dan praktis tanpa harus menggunakan sistem pemesanan online yang kompleks.</p>',
                'features' => [
                    ['Beranda', 'Menampilkan layanan unggulan, kategori layanan, informasi tentang DUYUFUNA, testimoni pelanggan, dan artikel terbaru.'],
                    ['Katalog Layanan', 'Layanan tersaji rapi per kategori: Hotel, Visa, Transportasi, Handling Bandara, Catering, Laundry, Muthawif, dan lainnya, lengkap dengan foto dan harga.'],
                    ['Detail Layanan', 'Setiap layanan memiliki halaman khusus berisi lokasi, fasilitas, tipe kamar, pilihan kendaraan, rute, dan kapasitas.'],
                    ['Integrasi WhatsApp', 'Tombol WhatsApp di berbagai bagian website dengan pesan otomatis sesuai layanan yang dilihat.'],
                    ['Admin Panel', 'Seluruh konten dikelola melalui admin panel: layanan, kategori, banner, artikel, galeri, FAQ, dan testimoni.'],
                    ['Galeri Kegiatan', 'Dokumentasi aktivitas di Tanah Suci seperti ziarah, city tour, Raudhah, dan perjalanan kereta cepat.'],
                    ['Desain Mobile First', 'Ringan, nyaman dibaca, mudah dinavigasi, dan responsif di semua perangkat.'],
                    ['SEO Lengkap', 'Dilengkapi sitemap.xml, robots.txt, meta SEO, dan Open Graph.'],
                ],
            ],
            [
                'name' => 'Website RDR Interior Aluminium',
                'slug' => 'website-rdr-interior-aluminium',
                'category' => 'company',
                'client' => 'RDR Interior Aluminium',
                'description' => '<p>Website ini dirancang sebagai company profile sekaligus katalog digital untuk menampilkan produk interior aluminium custom. Mulai dari kitchen set, kabinet dan lemari, pintu, jendela, partisi ruangan, hingga cermin aksesoris.</p><p>Seluruh produk diproduksi oleh RDR Interior Aluminium Semarang, spesialis fabrikasi aluminium dengan finishing ACP premium, ditujukan untuk kebutuhan rumah tinggal, proyek properti, dan komersial.</p><p>Melalui website ini, calon pelanggan dapat menjelajahi koleksi produk secara lengkap, melihat spesifikasi bahan, warna, dimensi, dan estimasi pengerjaan, hingga menghubungi tim penjual langsung melalui WhatsApp.</p>',
                'features' => [
                    ['Beranda', 'Slider produk unggulan, kategori utama, portofolio proyek, dan testimoni pelanggan.'],
                    ['Katalog Produk', 'Produk tersusun rapi dalam 6 kategori: Kitchen Set, Kabinet dan Lemari, Pintu Aluminium, Jendela Aluminium, Partisi Ruangan, serta Cermin dan Aksesoris.'],
                    ['Detail Produk', 'Galeri multi-image, spesifikasi material, warna, dimensi, estimasi pengerjaan, dan tombol pembelian Tokopedia, Shopee, dan TikTok Shop.'],
                    ['Integrasi WhatsApp', 'Tombol WhatsApp floating di setiap halaman untuk konsultasi dan pemesanan.'],
                    ['Portofolio dan Testimoni', 'Galeri proyek dan ulasan pelanggan untuk membangun kepercayaan.'],
                    ['Desain Responsif', 'Tampilan menyesuaikan smartphone, tablet, maupun desktop.'],
                ],
            ],
            [
                'name' => 'Website Horeca Furniture',
                'slug' => 'website-horeca-furniture-2',
                'category' => 'company',
                'client' => 'Horeca Furniture',
                'description' => '<p>Website ini dirancang sebagai company profile sekaligus media katalog digital untuk menampilkan berbagai produk mebel kayu jati, seperti kursi dan meja, yang ditujukan untuk kebutuhan Hotel, Restoran, dan Cafe (Horeca).</p><p>Melalui website ini, calon pelanggan dapat melihat koleksi produk secara lengkap, memperoleh informasi spesifikasi, hingga menghubungi penjual secara langsung melalui WhatsApp.</p><p>Solusi ini sangat cocok bagi pelaku usaha yang ingin memperluas jangkauan pemasaran dengan tampilan yang profesional, tanpa harus menggunakan sistem toko online yang kompleks.</p>',
                'features' => [
                    ['Beranda', 'Menampilkan produk unggulan, kategori utama, serta informasi singkat mengenai usaha.'],
                    ['Katalog Produk', 'Daftar produk tersaji rapi dengan foto, deskripsi, dan harga.'],
                    ['Detail Produk', 'Halaman khusus berisi galeri foto, spesifikasi bahan, dan ukuran.'],
                    ['Integrasi WhatsApp', 'Tombol WhatsApp untuk konsultasi, penawaran harga, dan pemesanan.'],
                    ['Desain Responsif', 'Nyaman diakses melalui smartphone, tablet, maupun komputer.'],
                ],
            ],
            [
                'name' => 'Website Outdoor Furniture - InnovaTeak',
                'slug' => 'website-outdoor-furniture-innovateak-2',
                'category' => 'company',
                'client' => 'InnovaTeak',
                'description' => '<p>Website ini dirancang sebagai company profile sekaligus katalog digital untuk menampilkan berbagai produk furniture kayu jati outdoor, seperti kursi lipat, kursi stacking, bangku taman, meja lipat, sun lounger, hingga deep seating yang ditujukan untuk pasar lokal maupun internasional.</p><p>Melalui website ini, calon pembeli dapat menjelajahi koleksi produk secara lengkap, melihat spesifikasi tiap model, hingga menghubungi produsen langsung untuk penawaran harga dan pemesanan.</p><p>Solusi ini sangat cocok bagi perajin dan eksportir mebel yang ingin memperluas jangkauan pemasaran dengan tampilan profesional, tanpa harus menggunakan sistem toko online yang kompleks.</p>',
                'features' => [
                    ['Katalog Outdoor Furniture', 'Produk tersaji rapi per kategori: Folding Chair, Stacking Chair, Benches, Tables, Adirondacks, Sun Lounger, dan Deep Seating.'],
                    ['Product Detail', 'Setiap produk memiliki halaman khusus berisi kode model, deskripsi, dan spesifikasi.'],
                    ['Legal Wood Certified', 'Menonjolkan jaminan kayu bersertifikat legal untuk buyer internasional.'],
                    ['Payment Terms', 'Halaman khusus syarat pembayaran dan pemesanan.'],
                    ['Contact Us', 'Kontak langsung via email dan telepon untuk konsultasi dan penawaran.'],
                    ['Desain Responsif', 'Tampilan menyesuaikan berbagai ukuran layar.'],
                ],
            ],
            [
                'name' => 'Website Bukit Jati',
                'slug' => 'website-bukit-jati',
                'category' => 'company',
                'client' => 'Bukit Jati',
                'description' => '<p>Website ini dirancang sebagai company profile sekaligus media katalog digital untuk menampilkan berbagai produk mebel kayu jati indoor dan outdoor, seperti kursi lipat, bangku, meja expansion, dan sofa taman, yang ditujukan untuk kebutuhan Hotel, Restoran, Cafe, dan taman komersial (Horeca).</p><p>Melalui website ini, calon pelanggan dapat melihat koleksi produk secara lengkap, memperoleh informasi spesifikasi, hingga menghubungi penjual secara langsung melalui WhatsApp.</p><p>Solusi ini sangat cocok bagi pelaku usaha yang ingin memperluas jangkauan pemasaran dengan tampilan yang profesional, tanpa harus menggunakan sistem toko online yang kompleks.</p>',
                'features' => [
                    ['Beranda', 'Slider produk, kategori utama, best seller products, serta statistik produksi dan ekspor.'],
                    ['Katalog Produk', 'Sembilan kategori jelas: Folding Chair, Stacking Chair, Benches, Lounge, Extension Table, Fixed Table, Folding Table, Outdoor Table, dan Sofa Set.'],
                    ['Detail Produk', 'Foto galeri, spesifikasi bahan jati, ukuran, dan deskripsi produk.'],
                    ['Request Complete Catalog', 'Formulir permintaan katalog lengkap via email.'],
                    ['Integrasi WhatsApp', 'Tombol WhatsApp floating dan tombol Contact Us.'],
                    ['Desain Responsif', 'Nyaman diakses via smartphone, tablet, maupun desktop.'],
                ],
            ],
            [
                'name' => 'Website Mury Furniture',
                'slug' => 'website-mury-furniture',
                'category' => 'company',
                'client' => 'Mury Furniture',
                'description' => '<p>Website ini dirancang sebagai company profile sekaligus media katalog digital untuk menampilkan berbagai produk mebel klasik Prancis dan custom wood finishes, yang diproduksi oleh pengrajin Jepara dan ditujukan untuk kebutuhan ekspor, retail, dan proyek interior.</p><p>Melalui website ini, calon pelanggan seperti buyer luar negeri, retailer, arsitek, dan kontraktor dapat melihat koleksi produk secara lengkap, memperoleh informasi spesifikasi bahan dan pilihan finishing, hingga menghubungi penjual langsung melalui WhatsApp.</p><p>Solusi ini sangat cocok bagi produsen mebel yang ingin memperluas jangkauan pasar global dengan tampilan yang elegan dan profesional, tanpa harus menggunakan sistem toko online yang kompleks.</p>',
                'features' => [
                    ['Beranda', 'Slider produk unggulan, kategori utama, cerita brand, timeline perusahaan, serta statistik produksi dan ekspor.'],
                    ['Katalog Produk', 'Dua belas kategori rapi: kursi, meja, tempat tidur, lemari, hingga finishing colour.'],
                    ['Detail Produk', 'Foto produk, spesifikasi, deskripsi, serta informasi bahan dan finishing.'],
                    ['Dokumentasi Produksi', 'Foto proses produksi, workshop, dan quality control.'],
                    ['Multi-Bahasa dan Blog', 'Konten berbahasa Inggris untuk pasar internasional, dilengkapi blog berita dan event ekspor.'],
                    ['Integrasi WhatsApp', 'Tombol WhatsApp floating di setiap halaman.'],
                ],
            ],
            [
                'name' => 'SMOPF - Sistem Manajemen Order & Produksi Furniture',
                'slug' => 'smopf-sistem-manajemen-order-produksi-furniture',
                'category' => 'sistem',
                'client' => null,
                'description' => '<p>Sistem manajemen digital untuk mengelola seluruh alur produksi furniture, mulai dari penerimaan order, tahap pengerjaan, hingga barang siap kirim. Ditujukan untuk pengrajin, UMKM, maupun pengusaha mebel yang ingin memantau produksi secara terstruktur dan real-time.</p><p>Melalui sistem ini, pemilik usaha dapat mencatat order dari buyer lokal maupun export, mengelola data pekerja, melihat progres produksi per item, hingga menghasilkan laporan dalam bentuk PDF maupun Excel.</p><p>Solusi ini sangat cocok bagi pelaku usaha yang ingin naik kelas ke sistem profesional tanpa harus mencatat produksi secara manual di buku atau Excel.</p>',
                'features' => [
                    ['Dashboard', 'Ringkasan produksi, order aktif, dan status pengerjaan dengan filter Harian, Mingguan, Bulanan, hingga Tahunan.'],
                    ['Data Buyer', 'Data pembeli lokal maupun export lengkap dengan alamat, kontak, dan NPWP.'],
                    ['Data Pekerja', 'Kelola pengrajin, tukang amplas, dan tukang finishing beserta penugasan dan setoran hasil kerja.'],
                    ['Order dan Produksi', 'Alur per item: Setor, Amplas, Finishing, QC, dan Packing dengan status real-time.'],
                    ['Laporan', 'Rekap order, pengrajin, amplas, dan finishing yang dapat diekspor ke PDF, Excel, maupun CSV.'],
                    ['Stok Siap Kirim', 'Daftar produk lulus QC yang siap dikemas dan dikirim ke buyer.'],
                    ['Manajemen Admin', 'Kelola akun pengguna beserta peran akses.'],
                ],
            ],
            [
                'name' => 'Aplikasi Manajemen Service HP Juneo Phone',
                'slug' => 'aplikasi-manajemen-service-hp-juneo-phone',
                'category' => 'sistem',
                'client' => 'Juneo Phone',
                'description' => '<p>Aplikasi manajemen service HP yang membantu pemilik usaha mengelola seluruh kegiatan perbaikan dalam satu sistem: mencatat service pelanggan, memantau status service, mengatur antrian, hingga menyusun laporan keuangan secara terstruktur.</p><p>Tujuan utamanya adalah memudahkan pemilik usaha dalam mengelola pekerjaan dan keuangan, sehingga administrasi lebih rapi dan progres service lebih terkontrol.</p>',
                'features' => [
                    ['Dashboard', 'Ringkasan omzet, pengeluaran, dan profit real-time lengkap dengan grafik informatif.'],
                    ['Data Service', 'Daftar service dengan status, pelanggan, dan detail kerusakan HP dalam satu layar.'],
                    ['Antrian Service', 'Daftar service terurut berdasarkan prioritas dan estimasi selesai.'],
                    ['Laporan', 'Rekap data yang dapat diekspor ke Excel atau PDF.'],
                    ['Biaya Harian', 'Pencatatan biaya harian dan fee karyawan.'],
                    ['Desain Mobile-Friendly', 'Nyaman diakses melalui smartphone maupun komputer.'],
                ],
            ],
        ];

        // Dibuat dari belakang agar urutan created_at desc = urutan tampil di live.
        $order = 1;
        foreach (array_reverse($projects) as $data) {
            $category = ProjectCategory::where('slug', $data['category'])->first();
            unset($data['category']);

            $features = $data['features'];
            unset($data['features']);

            $row = Project::withTrashed()->firstOrNew(['slug' => $data['slug']]);
            if ($row->trashed()) {
                $row->restore();
            }
            $row->fill($data + [
                'category_id' => $category?->id,
                'url' => null,
                'year' => null,
                'is_featured' => true,
                'status' => true,
                'sort_order' => $order++,
            ])->save();

            if ($category) {
                $row->categories()->syncWithoutDetaching([$category->id]);
            }

            foreach ($features as $i => [$title, $desc]) {
                $row->features()->updateOrCreate(
                    ['feature' => $title],
                    ['description' => $desc, 'sort_order' => $i + 1]
                );
            }

            foreach ($techStack as $i => $tech) {
                $row->technologies()->firstOrCreate(
                    ['name' => $tech],
                    ['sort_order' => $i + 1]
                );
            }
        }

        $this->command->info('Projects seeded successfully.');
    }
}
