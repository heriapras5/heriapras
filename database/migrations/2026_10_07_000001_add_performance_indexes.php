<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index untuk pola query yang paling sering dipakai:
     * - messages: filter is_read + urut created_at (daftar admin + badge unread)
     * - projects: listing aktif/unggulan (homepage, CV, pagination)
     * - media: urut sort_order
     * seo_meta tidak perlu index tambahan: morphs() sudah membuat
     * index komposit (pageable_type, pageable_id).
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['is_read', 'created_at']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->index(['status', 'is_featured']);
            $table->index(['status', 'created_at']);
        });
        Schema::table('media', function (Blueprint $table) {
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status', 'is_featured']);
            $table->dropIndex(['status', 'created_at']);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['is_read', 'created_at']);
        });
    }
};
