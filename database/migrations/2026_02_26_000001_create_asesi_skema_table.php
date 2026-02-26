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
        Schema::create('asesi_skema', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->foreignId('skema_id')->constrained('skemas')->onDelete('cascade');
            $table->enum('status', ['belum_mulai', 'sedang_mengerjakan', 'selesai'])->default('belum_mulai');
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['asesi_nik', 'skema_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesi_skema');
    }
};
