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
        if (!Schema::hasTable('rekaman_asesmen_kompetensi')) {
            Schema::create('rekaman_asesmen_kompetensi', function (Blueprint $table) {
                $table->id();
                $table->string('kode_form', 20)->default('FR.AK.02.');
                $table->string('judul_form')->default('REKAMAN ASESMEN KOMPETENSI');
                $table->string('kategori_skema', 100)->nullable()->default('KKNI/Okupasi/Klaster');
                $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
                $table->string('tuk')->nullable()->default('Sewaktu/Tempat Kerja/Mandiri*');
                $table->unsignedBigInteger('asesor_id')->nullable();
                $table->string('asesi_nik');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->enum('rekomendasi', ['kompeten', 'belum_kompeten'])->default('belum_kompeten');
                $table->text('tindak_lanjut')->nullable();
                $table->text('komentar_observasi')->nullable();
                $table->string('catatan_footer')->nullable()->default('* Coret yang tidak perlu');
                $table->timestamps();

                $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->cascadeOnDelete();
                $table->index(['skema_id', 'asesi_nik']);
            });
        }

        Schema::dropIfExists('rekaman_asesmen_kompetensi_details');

        Schema::create('rekaman_asesmen_kompetensi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekaman_id');
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->boolean('observasi_demonstrasi')->default(false);
            $table->boolean('portofolio')->default(false);
            $table->boolean('pernyataan_pihak_ketiga')->default(false);
            $table->boolean('pertanyaan_lisan')->default(false);
            $table->boolean('pertanyaan_tertulis')->default(false);
            $table->boolean('proyek_kerja')->default(false);
            $table->boolean('lainnya')->default(false);
            $table->timestamps();

            $table->foreign('rekaman_id', 'fk_rak_detail_rekaman')
                ->references('id')
                ->on('rekaman_asesmen_kompetensi')
                ->cascadeOnDelete();

            $table->unique(['rekaman_id', 'unit_id'], 'uniq_rak_rekaman_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekaman_asesmen_kompetensi_details');
        Schema::dropIfExists('rekaman_asesmen_kompetensi');
    }
};
