<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_persyaratan_dasar_pemohon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skema_id')->unique()->constrained('skemas')->cascadeOnDelete();
            $table->json('items');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_persyaratan_dasar_pemohon');
    }
};