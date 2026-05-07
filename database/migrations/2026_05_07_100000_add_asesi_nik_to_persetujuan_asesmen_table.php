<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persetujuan_asesmen', function (Blueprint $table) {
            if (!Schema::hasColumn('persetujuan_asesmen', 'asesi_nik')) {
                $table->string('asesi_nik')->nullable()->after('nama_asesi');
                $table->index('asesi_nik');
            }
        });
    }

    public function down(): void
    {
        Schema::table('persetujuan_asesmen', function (Blueprint $table) {
            if (Schema::hasColumn('persetujuan_asesmen', 'asesi_nik')) {
                $table->dropIndex(['asesi_nik']);
                $table->dropColumn('asesi_nik');
            }
        });
    }
};
