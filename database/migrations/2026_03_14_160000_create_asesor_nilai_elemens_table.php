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
        Schema::create('asesor_nilai_elemens', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->foreignId('skema_id')->constrained('skemas')->onDelete('cascade');
            $table->foreignId('elemen_id')->constrained('elemens')->onDelete('cascade');
            $table->unsignedBigInteger('asesor_id')->nullable();
            $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
            $table->unsignedTinyInteger('nilai')->default(0);
            $table->enum('status', ['K', 'BK'])->default('BK');
            $table->timestamps();

            $table->unique(['asesi_nik', 'skema_id', 'elemen_id', 'asesor_id'], 'uniq_asesor_nilai_elemen');
            $table->index(['skema_id', 'asesor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor_nilai_elemens');
    }
};
