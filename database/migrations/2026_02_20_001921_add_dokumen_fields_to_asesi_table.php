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
        Schema::table('asesi', function (Blueprint $table) {
            $table->string('pas_foto')->nullable()->after('unit_lembaga');
            $table->string('identitas_pribadi')->nullable()->after('pas_foto');
            $table->string('bukti_kompetensi')->nullable()->after('identitas_pribadi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn(['pas_foto', 'identitas_pribadi', 'bukti_kompetensi']);
        });
    }
};
