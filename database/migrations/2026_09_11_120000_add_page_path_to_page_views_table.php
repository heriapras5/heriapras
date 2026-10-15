<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('page_path')->nullable()->after('page_url');
            $table->index('page_path');
        });

        // Backfill: ekstrak path murni dari page_url lama agar pengelompokan
        // "halaman terpopuler" tidak terpecah oleh query string/UTM.
        // Format konsisten dengan $request->path(): "/" untuk root, tanpa slash awal selain itu.
        DB::table('page_views')->whereNull('page_path')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                $path = parse_url($row->page_url ?? '', PHP_URL_PATH);
                if ($path === false || $path === null || $path === '') {
                    $path = '/';
                } else {
                    $path = ltrim($path, '/');
                }
                DB::table('page_views')->where('id', $row->id)->update([
                    'page_path' => mb_substr($path, 0, 255),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropIndex(['page_path']);
            $table->dropColumn('page_path');
        });
    }
};
