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
        Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
            $table->string('ttd_asesor_nama')->nullable();
            $table->string('ttd_asesor_no_reg')->nullable();
            $table->date('ttd_asesor_tanggal')->nullable();
            $table->text('ttd_asesor_file')->nullable();
            $table->string('ttd_asesi_nama')->nullable();
            $table->date('ttd_asesi_tanggal')->nullable();
            $table->text('ttd_asesi_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_asesor_nama',
                'ttd_asesor_no_reg',
                'ttd_asesor_tanggal',
                'ttd_asesor_file',
                'ttd_asesi_nama',
                'ttd_asesi_tanggal',
                'ttd_asesi_file',
            ]);
        });
    }
};
