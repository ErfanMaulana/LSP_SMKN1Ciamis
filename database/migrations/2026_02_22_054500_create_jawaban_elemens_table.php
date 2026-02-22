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
        Schema::create('jawaban_elemens', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->foreignId('elemen_id')->constrained('elemens')->onDelete('cascade');
            $table->enum('status', ['K', 'BK'])->default('BK')->comment('K=Kompeten, BK=Belum Kompeten');
            $table->text('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_elemens');
    }
};
