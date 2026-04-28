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
        if (!Schema::hasTable('ceklis_observasi_aktivitas_praktiks')) {
            Schema::create('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
                $table->id();
                $table->string('kode_form', 20)->default('FR.IA.01.');
                $table->string('judul_form')->default('CEKLIS OBSERVASI AKTIVITAS PRAKTIK');
                $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
                $table->string('asesi_nik');
                $table->unsignedBigInteger('asesor_id')->nullable();
                $table->string('tuk')->nullable();
                $table->date('tanggal')->nullable();
                $table->enum('rekomendasi', ['kompeten', 'belum_kompeten'])->default('belum_kompeten');
                $table->string('belum_kompeten_kelompok_pekerjaan')->nullable();
                $table->string('belum_kompeten_unit')->nullable();
                $table->string('belum_kompeten_elemen')->nullable();
                $table->string('belum_kompeten_kuk')->nullable();
                $table->string('ttd_asesi_nama')->nullable();
                $table->date('ttd_asesi_tanggal')->nullable();
                $table->string('ttd_asesor_nama')->nullable();
                $table->string('ttd_asesor_no_reg')->nullable();
                $table->date('ttd_asesor_tanggal')->nullable();
                $table->string('catatan_footer')->nullable();
                $table->timestamps();

                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->cascadeOnDelete();
                $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
                $table->index(['skema_id', 'asesi_nik']);
                $table->index(['asesor_id']);
            });
        }

        // Recreate details table to ensure constraints exist with short FK names.
        Schema::dropIfExists('ceklis_observasi_aktivitas_praktik_details');

        Schema::create('ceklis_observasi_aktivitas_praktik_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ceklis_observasi_id');
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('elemen_id')->constrained('elemens')->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
            $table->enum('pencapaian', ['ya', 'tidak'])->nullable();
            $table->text('penilaian_lanjut')->nullable();
            $table->timestamps();

            $table->foreign('ceklis_observasi_id', 'fk_cop_detail_obs')
                ->references('id')
                ->on('ceklis_observasi_aktivitas_praktiks')
                ->cascadeOnDelete();
            $table->unique(['ceklis_observasi_id', 'kriteria_id'], 'uniq_ceklis_observasi_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceklis_observasi_aktivitas_praktik_details');
        Schema::dropIfExists('ceklis_observasi_aktivitas_praktiks');
    }
};
