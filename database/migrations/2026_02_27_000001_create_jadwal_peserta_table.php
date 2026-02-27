<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jadwal_peserta')) {
            return;
        }

        Schema::create('jadwal_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_ujikom')->onDelete('cascade');
            $table->string('asesi_nik');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['jadwal_id', 'asesi_nik']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_peserta');
    }
};
