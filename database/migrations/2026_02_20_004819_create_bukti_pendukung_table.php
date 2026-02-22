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
        Schema::create('bukti_pendukung', function (Blueprint $table) {
            $table->id();
            $table->string('NIK');
            $table->enum('jenis_dokumen', ['transkrip_nilai', 'identitas_pribadi', 'bukti_kompetensi']);
            $table->string('file_path');
            $table->string('nama_file');
            $table->timestamps();

            $table->foreign('NIK')->references('NIK')->on('asesi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_pendukung');
    }
};
