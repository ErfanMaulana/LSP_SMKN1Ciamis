<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_ujikom', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuk_id')->nullable();
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->string('judul_jadwal');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('kuota')->default(1);
            $table->integer('peserta_terdaftar')->default(0);
            $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('tuk_id')->references('id')->on('tuk')->onDelete('set null');
            $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_ujikom');
    }
};
