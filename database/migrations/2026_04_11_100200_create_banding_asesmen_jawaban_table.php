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
        Schema::create('banding_asesmen_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banding_id')->constrained('banding_asesmen')->cascadeOnDelete();
            $table->foreignId('komponen_id')->constrained('banding_asesmen_komponen')->cascadeOnDelete();
            $table->enum('jawaban', ['ya', 'tidak']);
            $table->timestamps();

            $table->unique(['banding_id', 'komponen_id'], 'banding_asesmen_jawaban_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banding_asesmen_jawaban');
    }
};
