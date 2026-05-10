<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            if (!Schema::hasColumn('ceklis_observasi_aktivitas_praktiks', 'ttd_asesor_file')) {
                $table->string('ttd_asesor_file')->nullable()->after('ttd_asesor_tanggal');
            }
            if (!Schema::hasColumn('ceklis_observasi_aktivitas_praktiks', 'ttd_asesi_file')) {
                $table->string('ttd_asesi_file')->nullable()->after('ttd_asesi_tanggal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            if (Schema::hasColumn('ceklis_observasi_aktivitas_praktiks', 'ttd_asesor_file')) {
                $table->dropColumn('ttd_asesor_file');
            }
            if (Schema::hasColumn('ceklis_observasi_aktivitas_praktiks', 'ttd_asesi_file')) {
                $table->dropColumn('ttd_asesi_file');
            }
        });
    }
};
