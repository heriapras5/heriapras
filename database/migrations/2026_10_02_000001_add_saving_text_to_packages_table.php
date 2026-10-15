<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('saving_text')->nullable()->after('old_price');
        });

        // Backfill nilai promo yang sedang berjalan di produksi.
        DB::table('packages')->where('slug', 'inoweb-start')->update(['saving_text' => 'Hemat 250rb']);
        DB::table('packages')->where('slug', 'inoweb-business')->update(['saving_text' => 'Hemat 500rb']);
        DB::table('packages')->where('slug', 'inoweb-pro')->update(['saving_text' => 'Hemat 750rb']);
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('saving_text');
        });
    }
};
