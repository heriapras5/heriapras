<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menyelaraskan kolom icon menjadi TEXT. Migrasi create sudah memakai
     * text(), jadi di sini hanya menambal database MySQL lama yang masih
     * VARCHAR(255). Idempoten: kolom yang sudah TEXT dilewati.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (['services', 'project_technologies', 'project_results'] as $table) {
            $type = strtolower((string) (DB::selectOne(
                'SELECT COLUMN_TYPE AS t FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$table, 'icon']
            )?->t ?? ''));
            if ($type !== '' && ! str_contains($type, 'text')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `icon` TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (['services', 'project_technologies', 'project_results'] as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `icon` VARCHAR(255) NULL");
        }
    }
};
