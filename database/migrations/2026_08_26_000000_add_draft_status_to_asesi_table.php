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
        // Modify enum to include 'draft' for MySQL/MariaDB
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE asesi MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'banned') NOT NULL DEFAULT 'draft' COMMENT 'Status verifikasi: draft=belum submit dokumen, pending=menunggu verifikasi, approved=disetujui, rejected=ditolak sementara, banned=ditolak permanen'");
        }

        // Update any existing pending asesi that haven't uploaded signature to draft
        DB::statement("UPDATE asesi SET status = 'draft' WHERE status = 'pending' AND (tanda_tangan_pendaftar IS NULL OR tanda_tangan_pendaftar = '')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE asesi SET status = 'pending' WHERE status = 'draft'");
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE asesi MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'banned') NOT NULL DEFAULT 'pending' COMMENT 'Status verifikasi: pending=menunggu, approved=disetujui, rejected=ditolak sementara, banned=ditolak permanen'");
        }
    }
};
