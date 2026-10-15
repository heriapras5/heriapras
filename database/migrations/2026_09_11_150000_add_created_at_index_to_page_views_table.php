<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Semua query laporan dashboard memfilter/mengelompokkan berdasarkan
        // created_at, jadi kolom ini butuh index agar tidak full table scan.
        Schema::table('page_views', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
