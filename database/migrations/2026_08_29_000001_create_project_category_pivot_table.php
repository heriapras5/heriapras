<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_category_id')->constrained('project_categories')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['project_id', 'project_category_id']);
        });

        $rows = DB::table('projects')
            ->whereNotNull('category_id')
            ->get(['id as project_id', 'category_id as project_category_id'])
            ->map(fn($r) => (array) $r)
            ->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            $insert = array_map(fn($r) => $r + ['created_at' => now(), 'updated_at' => now()], $chunk);
            DB::table('project_category')->insert($insert);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_category');
    }
};