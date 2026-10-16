<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin utama untuk masuk ke /admin. Kredensial diambil dari
        // config/admin.php (sumber: ADMIN_* di .env) agar tidak ter-commit
        // ke repo dan tetap jalan saat config:cache.
        $email = (string) config('admin.email');
        $password = (string) config('admin.password');
        $generated = false;
        if ($password === '') {
            $password = Str::random(16);
            $generated = true;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) config('admin.name'),
                'password' => Hash::make($password),
                'is_admin' => true,
                'role' => 'super_admin',
            ]
        );

        if ($generated && $this->command) {
            $this->command->warn("ADMIN_PASSWORD kosong: admin {$email} dibuat dengan password acak: {$password}");
            $this->command->warn('Simpan password di atas, lalu isi ADMIN_PASSWORD di .env dan seed ulang (atau reset via tinker).');
        }

        $this->call([
            SettingsSeeder::class,
            TimelineItemSeeder::class,
            ServiceSeeder::class,
            ProjectCategorySeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
