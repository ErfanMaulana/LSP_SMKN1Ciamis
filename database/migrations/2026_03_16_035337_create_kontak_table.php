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
        Schema::create('kontak', function (Blueprint $table) {
            $table->id();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('telepon_whatsapp')->nullable();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
            $table->json('jam_pelayanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak');
    }
};
