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
        $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        // 1. asesi_skema
        Schema::table('asesi_skema', function (Blueprint $table) use ($isSqlite) {
            if (!$isSqlite) {
                $table->dropForeign('asesi_skema_asesi_nik_foreign');
                $table->dropForeign('asesi_skema_skema_id_foreign');
            }
            $table->dropUnique('asesi_skema_asesi_nik_skema_id_unique');
        });
        Schema::table('asesi_skema', function (Blueprint $table) use ($isSqlite) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('skema_id');
            $table->unique(['asesi_nik', 'skema_id', 'attempt'], 'uniq_asesi_skema_attempt');
            if (!$isSqlite) {
                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
                $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('cascade');
            }
        });

        // 2. jawaban_elemens
        Schema::table('jawaban_elemens', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('elemen_id');
        });

        // 3. asesor_nilai_elemens
        Schema::table('asesor_nilai_elemens', function (Blueprint $table) use ($isSqlite) {
            if (!$isSqlite) {
                $table->dropForeign('asesor_nilai_elemens_asesi_nik_foreign');
                $table->dropForeign('asesor_nilai_elemens_skema_id_foreign');
                $table->dropForeign('asesor_nilai_elemens_elemen_id_foreign');
                $table->dropForeign('asesor_nilai_elemens_asesor_id_foreign');
            }
            $table->dropUnique('uniq_asesor_nilai_elemen');
        });
        Schema::table('asesor_nilai_elemens', function (Blueprint $table) use ($isSqlite) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('elemen_id');
            $table->unique(['asesi_nik', 'skema_id', 'elemen_id', 'asesor_id', 'attempt'], 'uniq_asesor_nilai_elemen_attempt');
            if (!$isSqlite) {
                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
                $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('cascade');
                $table->foreign('elemen_id')->references('id')->on('elemens')->onDelete('cascade');
                $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
            }
        });

        // 4. persetujuan_asesmen
        Schema::table('persetujuan_asesmen', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('id');
        });

        // 5. ceklis_observasi_aktivitas_praktiks
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('id');
        });

        // 6. rekaman_asesmen_kompetensi
        Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('id');
        });

        // 7. umpan_balik_hasil
        Schema::table('umpan_balik_hasil', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempt')->default(1)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        // 1. asesi_skema
        Schema::table('asesi_skema', function (Blueprint $table) use ($isSqlite) {
            if (!$isSqlite) {
                $table->dropForeign('asesi_skema_asesi_nik_foreign');
                $table->dropForeign('asesi_skema_skema_id_foreign');
            }
            $table->dropUnique('uniq_asesi_skema_attempt');
        });
        Schema::table('asesi_skema', function (Blueprint $table) use ($isSqlite) {
            $table->dropColumn('attempt');
            $table->unique(['asesi_nik', 'skema_id'], 'asesi_skema_asesi_nik_skema_id_unique');
            if (!$isSqlite) {
                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
                $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('cascade');
            }
        });

        // 2. jawaban_elemens
        Schema::table('jawaban_elemens', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });

        // 3. asesor_nilai_elemens
        Schema::table('asesor_nilai_elemens', function (Blueprint $table) use ($isSqlite) {
            if (!$isSqlite) {
                $table->dropForeign('asesor_nilai_elemens_asesi_nik_foreign');
                $table->dropForeign('asesor_nilai_elemens_skema_id_foreign');
                $table->dropForeign('asesor_nilai_elemens_elemen_id_foreign');
                $table->dropForeign('asesor_nilai_elemens_asesor_id_foreign');
            }
            $table->dropUnique('uniq_asesor_nilai_elemen_attempt');
        });
        Schema::table('asesor_nilai_elemens', function (Blueprint $table) use ($isSqlite) {
            $table->dropColumn('attempt');
            $table->unique(['asesi_nik', 'skema_id', 'elemen_id', 'asesor_id'], 'uniq_asesor_nilai_elemen');
            if (!$isSqlite) {
                $table->foreign('asesi_nik')->references('NIK')->on('asesi')->onDelete('cascade');
                $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('cascade');
                $table->foreign('elemen_id')->references('id')->on('elemens')->onDelete('cascade');
                $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->nullOnDelete();
            }
        });

        // 4. persetujuan_asesmen
        Schema::table('persetujuan_asesmen', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });

        // 5. ceklis_observasi_aktivitas_praktiks
        Schema::table('ceklis_observasi_aktivitas_praktiks', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });

        // 6. rekaman_asesmen_kompetensi
        Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });

        // 7. umpan_balik_hasil
        Schema::table('umpan_balik_hasil', function (Blueprint $table) {
            $table->dropColumn('attempt');
        });
    }
};
