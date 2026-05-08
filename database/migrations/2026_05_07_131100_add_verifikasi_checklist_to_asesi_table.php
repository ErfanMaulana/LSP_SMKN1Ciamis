<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            if (!Schema::hasColumn('asesi', 'verifikasi_bukti_persyaratan_dasar')) {
                $table->json('verifikasi_bukti_persyaratan_dasar')->nullable()->after('tanggal_tanda_tangan_admin');
            }

            if (!Schema::hasColumn('asesi', 'verifikasi_bukti_administratif')) {
                $table->json('verifikasi_bukti_administratif')->nullable()->after('verifikasi_bukti_persyaratan_dasar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            if (Schema::hasColumn('asesi', 'verifikasi_bukti_administratif')) {
                $table->dropColumn('verifikasi_bukti_administratif');
            }

            if (Schema::hasColumn('asesi', 'verifikasi_bukti_persyaratan_dasar')) {
                $table->dropColumn('verifikasi_bukti_persyaratan_dasar');
            }
        });
    }
};