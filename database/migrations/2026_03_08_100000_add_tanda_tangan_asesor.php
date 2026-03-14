<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tanda tangan asesor di setiap rekomendasi
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->longText('tanda_tangan_asesor')->nullable()->after('reviewed_by');
            $table->timestamp('tanggal_tanda_tangan_asesor')->nullable()->after('tanda_tangan_asesor');
        });

        // Tanda tangan tersimpan di profil asesor
        Schema::table('asesor', function (Blueprint $table) {
            $table->longText('saved_tanda_tangan')->nullable()->after('no_met');
        });
    }

    public function down(): void
    {
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->dropColumn(['tanda_tangan_asesor', 'tanggal_tanda_tangan_asesor']);
        });

        Schema::table('asesor', function (Blueprint $table) {
            $table->dropColumn('saved_tanda_tangan');
        });
    }
};
