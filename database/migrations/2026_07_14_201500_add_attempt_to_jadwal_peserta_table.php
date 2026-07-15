<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
      *
      * MySQL cannot drop a unique index while another index (e.g. a FK support index)
      * references the same columns. We work around this by doing DROP + ADD in a single
      * ALTER TABLE statement so MySQL sees it atomically.
     */
    public function up(): void
    {
        $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        if ($isSqlite) {
            Schema::table('jadwal_peserta', function (Blueprint $table) {
                $table->dropUnique('jadwal_peserta_jadwal_id_asesi_nik_unique');
            });
            Schema::table('jadwal_peserta', function (Blueprint $table) {
                $table->unsignedTinyInteger('attempt')->default(1)->after('asesi_nik');
                $table->unique(['jadwal_id', 'asesi_nik', 'attempt'], 'uniq_jadwal_peserta_attempt');
            });
        } else {
            // Single atomic ALTER TABLE: drop old unique index, add attempt column,
            // add new composite unique index, and recreate the supporting index for the FK.
            DB::statement('
                ALTER TABLE `jadwal_peserta`
                    DROP INDEX `jadwal_peserta_jadwal_id_asesi_nik_unique`,
                    ADD COLUMN `attempt` TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER `asesi_nik`,
                    ADD UNIQUE INDEX `uniq_jadwal_peserta_attempt` (`jadwal_id`, `asesi_nik`, `attempt`),
                    ADD INDEX `jadwal_peserta_asesi_nik_index` (`asesi_nik`)
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        if ($isSqlite) {
            Schema::table('jadwal_peserta', function (Blueprint $table) {
                $table->dropUnique('uniq_jadwal_peserta_attempt');
            });
            Schema::table('jadwal_peserta', function (Blueprint $table) {
                $table->dropColumn('attempt');
                $table->unique(['jadwal_id', 'asesi_nik'], 'jadwal_peserta_jadwal_id_asesi_nik_unique');
            });
        } else {
            DB::statement('
                ALTER TABLE `jadwal_peserta`
                    DROP INDEX `uniq_jadwal_peserta_attempt`,
                    DROP INDEX `jadwal_peserta_asesi_nik_index`,
                    DROP COLUMN `attempt`,
                    ADD UNIQUE INDEX `jadwal_peserta_jadwal_id_asesi_nik_unique` (`jadwal_id`, `asesi_nik`),
                    ADD INDEX `jadwal_peserta_asesi_nik_foreign` (`asesi_nik`)
            ');
        }
    }
};
