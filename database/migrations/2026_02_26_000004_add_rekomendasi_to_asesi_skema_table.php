<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->enum('rekomendasi', ['lanjut', 'tidak_lanjut'])->nullable()->after('tanggal_selesai')
                  ->comment('Rekomendasi asesor: lanjut = dapat dilanjutkan, tidak_lanjut = tidak dapat dilanjutkan');
            $table->text('catatan_asesor')->nullable()->after('rekomendasi');
            $table->timestamp('reviewed_at')->nullable()->after('catatan_asesor');
            $table->string('reviewed_by')->nullable()->after('reviewed_at')
                  ->comment('no_reg asesor yang memberikan rekomendasi');
        });
    }

    public function down(): void
    {
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->dropColumn(['rekomendasi', 'catatan_asesor', 'reviewed_at', 'reviewed_by']);
        });
    }
};
