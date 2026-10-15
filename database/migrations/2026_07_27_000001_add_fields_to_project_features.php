<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_features', function (Blueprint $table) {
            $table->text('description')->nullable()->after('feature');
            $table->text('icon')->nullable()->after('description');
        });

        Schema::table('project_images', function (Blueprint $table) {
            $table->string('caption')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('project_features', function (Blueprint $table) {
            $table->dropColumn(['description', 'icon']);
        });

        Schema::table('project_images', function (Blueprint $table) {
            $table->dropColumn('caption');
        });
    }
};
