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
        Schema::create('asesor', function (Blueprint $table) {
            $table->id('ID_asesor');
            $table->unsignedBigInteger('ID_skema')->nullable();
            $table->string('no_mou')->nullable();
            $table->string('nama');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('no_mou')->references('no_mou')->on('mitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor');
    }
};
