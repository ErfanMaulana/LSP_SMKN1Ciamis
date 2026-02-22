<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('pas_foto')
                  ->comment('Status verifikasi: pending=menunggu, approved=disetujui, rejected=ditolak');
            $table->text('catatan_admin')->nullable()->after('status')->comment('Catatan dari admin saat approval/reject');
            $table->timestamp('verified_at')->nullable()->after('catatan_admin')->comment('Waktu verifikasi oleh admin');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at')->comment('ID admin yang memverifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_admin', 'verified_at', 'verified_by']);
        });
    }
};
