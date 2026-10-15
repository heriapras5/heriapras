<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Company Profile', 'slug' => 'company', 'description' => 'Website company profile untuk bisnis dan organisasi.', 'sort_order' => 1],
            ['name' => 'Product Catalog', 'slug' => 'catalog', 'description' => 'Katalog produk digital dengan halaman detail.', 'sort_order' => 2],
            ['name' => 'Management System', 'slug' => 'sistem', 'description' => 'Sistem informasi untuk operasional dan manajemen.', 'sort_order' => 3],
            ['name' => 'Information System', 'slug' => 'information-system', 'description' => 'Sistem informasi berbasis web untuk kebutuhan organisasi.', 'sort_order' => 4],
            ['name' => 'Landing Page', 'slug' => 'landing', 'description' => 'Landing page untuk produk, campaign, atau promosi.', 'sort_order' => 5],
            ['name' => 'Custom Website', 'slug' => 'custom', 'description' => 'Website dengan fitur yang disesuaikan kebutuhan.', 'sort_order' => 6],
        ];

        foreach ($categories as $category) {
            $row = ProjectCategory::withTrashed()->firstOrNew(['slug' => $category['slug']]);
            if ($row->trashed()) {
                $row->restore();
            }
            $row->fill($category + ['status' => true])->save();
        }

        $this->command->info('Project categories seeded successfully.');
    }
}
