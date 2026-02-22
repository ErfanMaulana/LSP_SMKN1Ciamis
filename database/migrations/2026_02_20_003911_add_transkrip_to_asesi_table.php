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
            $table->string('transkrip_nilai')->nullable()->after('bukti_kompetensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn('transkrip_nilai');
        });
    }
};
