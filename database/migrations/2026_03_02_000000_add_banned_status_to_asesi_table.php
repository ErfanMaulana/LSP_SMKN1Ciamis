<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add 'banned' value
        DB::statement("ALTER TABLE asesi MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'banned') NOT NULL DEFAULT 'pending' COMMENT 'Status verifikasi: pending=menunggu, approved=disetujui, rejected=ditolak sementara, banned=ditolak permanen'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any 'banned' back to 'rejected' before removing the value
        DB::statement("UPDATE asesi SET status = 'rejected' WHERE status = 'banned'");
        DB::statement("ALTER TABLE asesi MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' COMMENT 'Status verifikasi: pending=menunggu, approved=disetujui, rejected=ditolak'");
    }
};
