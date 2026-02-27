<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_id');
            $table->string('asesi_nik');
            $table->timestamps();

            $table->foreign('jadwal_id')->references('id')->on('jadwal_ujikom')->onDelete('cascade');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->unique(['jadwal_id', 'asesi_nik']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_peserta');
    }
};
