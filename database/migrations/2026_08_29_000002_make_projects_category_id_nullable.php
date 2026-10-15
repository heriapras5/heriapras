<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tanpa doctrine/dbal, ->change() gagal di semua driver.
        // Pola drop + tambah ulang di bawah jalan di MySQL, SQLite,
        // dan PgSQL tanpa dependensi tambahan.
        $legacy = DB::table('projects')->pluck('category_id', 'id')->all();

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('project_categories')->cascadeOnDelete();
        });

        // Kembalikan nilai category_id yang sempat ter-drop agar kolom legacy
        // tetap terisi (sumber utama kategori tetap tabel pivot).
        foreach ($legacy as $id => $categoryId) {
            if ($categoryId !== null) {
                DB::table('projects')->where('id', $id)->update(['category_id' => $categoryId]);
            }
        }
    }

    public function down(): void
    {
        // Rollback ke NOT NULL hanya aman bila tidak ada baris dengan
        // category_id NULL. Isi dari tabel pivot project_category dulu
        // bila diperlukan sebelum migrate:rollback.
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('project_categories')->cascadeOnDelete();
        });
    }
};