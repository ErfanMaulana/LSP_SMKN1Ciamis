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
        Schema::create('asesi', function (Blueprint $table) {
            $table->string('NIK')->primary();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('ID_jurusan');
            $table->string('kelas')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kebangsaan')->nullable();
            $table->string('kode_kota')->nullable();
            $table->string('kode_provinsi')->nullable();
            $table->string('telepon_rumah')->nullable();
            $table->string('telepon_hp')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('pendidikan_terakhir')->nullable();

            $table->string('kode_kementrian')->nullable();
            $table->string('kode_anggaran')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('ID_jurusan')->references('ID_jurusan')->on('jurusan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesi');
    }
};
