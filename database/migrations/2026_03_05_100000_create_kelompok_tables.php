<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kelompok table
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->unsignedBigInteger('skema_id')->nullable();
            $table->foreign('skema_id')->references('id')->on('skemas')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Kelompok ↔ Asesor pivot
        Schema::create('kelompok_asesor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelompok_id');
            $table->unsignedBigInteger('asesor_id');
            $table->foreign('kelompok_id')->references('id')->on('kelompok')->cascadeOnDelete();
            $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->cascadeOnDelete();
            $table->unique(['kelompok_id', 'asesor_id']);
            $table->timestamps();
        });

        // 3. Add kelompok_id to asesi
        Schema::table('asesi', function (Blueprint $table) {
            $table->unsignedBigInteger('kelompok_id')->nullable()->after('ID_asesor');
            $table->foreign('kelompok_id')->references('id')->on('kelompok')->nullOnDelete();
        });

        // 4. Add kelompok_id to jadwal_ujikom
        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            $table->unsignedBigInteger('kelompok_id')->nullable()->after('asesor_id');
            $table->foreign('kelompok_id')->references('id')->on('kelompok')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn('kelompok_id');
        });

        Schema::table('asesi', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn('kelompok_id');
        });

        Schema::dropIfExists('kelompok_asesor');
        Schema::dropIfExists('kelompok');
    }
};
