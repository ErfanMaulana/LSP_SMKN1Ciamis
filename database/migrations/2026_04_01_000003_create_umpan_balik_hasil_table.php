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
        Schema::create('umpan_balik_hasil', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
            $table->foreignId('komponen_id')->constrained('umpan_balik_komponen')->cascadeOnDelete();
            $table->enum('jawaban', ['ya', 'tidak']);
            $table->text('catatan');
            $table->timestamps();

            $table->unique(['asesi_nik', 'skema_id', 'komponen_id'], 'umpan_balik_hasil_unique');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_balik_hasil');
    }
};
