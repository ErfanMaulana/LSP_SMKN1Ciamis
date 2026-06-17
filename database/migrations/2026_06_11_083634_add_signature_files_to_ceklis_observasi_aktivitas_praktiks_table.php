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
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            $table->string('ttd_asesor_file')->nullable()->after('ttd_asesor_tanggal');
            $table->string('ttd_asesi_file')->nullable()->after('ttd_asesi_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            $table->dropColumn(['ttd_asesor_file', 'ttd_asesi_file']);
        });
    }
};
