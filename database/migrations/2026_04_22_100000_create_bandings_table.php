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
        Schema::create('bandings', function (Blueprint $table) {
            $table->id();
            $table->string('asesi_nik');
            $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
            $table->foreignId('skema_id')->constrained('skemas')->onDelete('cascade');
            $table->foreignId('asesor_id')->nullable()->constrained('asesor', 'ID_asesor')->onDelete('set null');
            
            // Status banding
            $table->enum('status', ['pending', 'approved', 'rejected', 'revised'])->default('pending')
                  ->comment('pending=menunggu review, approved=disetujui, rejected=ditolak, revised=revisi nilai');
            
            // Alasan banding dari asesi
            $table->text('alasan_banding')->nullable()
                  ->comment('Alasan mengapa asesi merasa tidak setuju dengan hasil');
            
            // Detail nilai untuk referensi
            $table->integer('total_elemen')->nullable()
                  ->comment('Total jumlah elemen yang dinilai');
            $table->integer('total_k_sebelum')->nullable()
                  ->comment('Total elemen K (kompeten) sebelum banding');
            $table->integer('total_bk_sebelum')->nullable()
                  ->comment('Total elemen BK (belum kompeten) sebelum banding');
            
            // Review dari asesor
            $table->text('catatan_asesor')->nullable()
                  ->comment('Catatan atau penjelasan dari asesor');
            $table->integer('total_k_sesudah')->nullable()
                  ->comment('Total elemen K setelah review (jika direvisi)');
            $table->integer('total_bk_sesudah')->nullable()
                  ->comment('Total elemen BK setelah review (jika direvisi)');
            
            // Tracking
            $table->timestamp('diajukan_at')->nullable();
            $table->timestamp('direview_at')->nullable();
            $table->string('direview_oleh')->nullable()
                  ->comment('no_reg asesor yang review');
            
            $table->timestamps();
            
            // Unique constraint: satu asesi, satu skema, hanya satu banding aktif
            $table->unique(['asesi_nik', 'skema_id'], 'unique_active_banding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bandings');
    }
};
