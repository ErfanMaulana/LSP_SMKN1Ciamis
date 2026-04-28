<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('persetujuan_asesmen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_form', 20)->default('FR.AK.01.');
            $table->string('judul_form')->default('PERSETUJUAN ASESMEN DAN KERAHASIAAN');
            $table->text('pengantar');

            $table->string('kategori_skema', 100)->nullable();
            $table->string('judul_skema');
            $table->string('nomor_skema');
            $table->string('tuk')->nullable();
            $table->string('nama_asesor');
            $table->string('nama_asesi');

            $table->boolean('bukti_verifikasi_portofolio')->default(false);
            $table->boolean('bukti_reviu_produk')->default(false);
            $table->boolean('bukti_observasi_langsung')->default(false);
            $table->boolean('bukti_kegiatan_terstruktur')->default(false);
            $table->boolean('bukti_pertanyaan_lisan')->default(false);
            $table->boolean('bukti_pertanyaan_tertulis')->default(false);
            $table->boolean('bukti_pertanyaan_wawancara')->default(false);
            $table->boolean('bukti_lainnya')->default(false);
            $table->string('bukti_lainnya_keterangan')->nullable();

            $table->string('hari_tanggal', 120)->nullable();
            $table->string('waktu', 120)->nullable();
            $table->string('tuk_pelaksanaan')->nullable();

            $table->text('pernyataan_asesi_1');
            $table->text('pernyataan_asesor');
            $table->text('pernyataan_asesi_2');

            $table->string('ttd_asesor_nama')->nullable();
            $table->date('ttd_asesor_tanggal')->nullable();
            $table->string('ttd_asesi_nama')->nullable();
            $table->date('ttd_asesi_tanggal')->nullable();

            $table->string('catatan_footer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan_asesmen');
    }
};
