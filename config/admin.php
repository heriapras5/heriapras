<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun Administrator Awal
    |--------------------------------------------------------------------------
    |
    | Kredensial admin pertama yang dibuat oleh DatabaseSeeder.
    | Isi ADMIN_* di .env (lihat .env.example) dan JANGAN commit nilainya.
    | Bila ADMIN_PASSWORD dikosongkan, seeder membuat password acak sekali
    | pakai dan menampilkannya di console.
    |
    */

    'name' => env('ADMIN_NAME', 'Administrator'),
    'email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'password' => env('ADMIN_PASSWORD', ''),

];
