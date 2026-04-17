<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE banding_asesmen MODIFY COLUMN status ENUM('diajukan','ditinjau','diterima','ditolak','tidak_banding') NOT NULL DEFAULT 'diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE banding_asesmen MODIFY COLUMN status ENUM('diajukan','ditinjau','diterima','ditolak') NOT NULL DEFAULT 'diajukan'");
    }
};
