<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->longText('tanda_tangan_pendaftar')->nullable()->after('unit_lembaga');
            $table->timestamp('tanggal_tanda_tangan_pendaftar')->nullable()->after('tanda_tangan_pendaftar');
            $table->longText('tanda_tangan_admin')->nullable()->after('verified_by');
            $table->timestamp('tanggal_tanda_tangan_admin')->nullable()->after('tanda_tangan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn([
                'tanda_tangan_pendaftar',
                'tanggal_tanda_tangan_pendaftar',
                'tanda_tangan_admin',
                'tanggal_tanda_tangan_admin',
            ]);
        });
    }
};