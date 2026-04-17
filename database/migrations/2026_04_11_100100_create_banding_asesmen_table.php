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
        Schema::create('banding_asesmen', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
            $table->unsignedBigInteger('asesor_id')->nullable();
            $table->date('tanggal_asesmen')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->text('alasan_banding');
            $table->enum('status', ['diajukan', 'ditinjau', 'diterima', 'ditolak'])->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->unique(['asesi_nik', 'skema_id'], 'banding_asesmen_unique');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banding_asesmen');
    }
};
