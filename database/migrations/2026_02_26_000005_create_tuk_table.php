<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tuk');
            $table->string('kode_tuk')->unique()->nullable();
            $table->enum('tipe_tuk', ['sewaktu', 'tempat_kerja', 'mandiri'])->default('sewaktu');
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->integer('kapasitas')->default(0)->comment('Jumlah peserta maksimal');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tuk');
    }
};
