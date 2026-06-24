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
        Schema::table('banding_asesmen', function (Blueprint $table) {
            $table->string('ttd_asesi_nama')->nullable()->after('catatan_admin');
            $table->date('ttd_asesi_tanggal')->nullable()->after('ttd_asesi_nama');
            $table->text('ttd_asesi_file')->nullable()->after('ttd_asesi_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banding_asesmen', function (Blueprint $table) {
            $table->dropColumn(['ttd_asesi_nama', 'ttd_asesi_tanggal', 'ttd_asesi_file']);
        });
    }
};
