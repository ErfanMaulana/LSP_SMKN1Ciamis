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
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->longText('tanda_tangan')->nullable()->after('tanggal_selesai');
            $table->timestamp('tanggal_tanda_tangan')->nullable()->after('tanda_tangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi_skema', function (Blueprint $table) {
            $table->dropColumn(['tanda_tangan', 'tanggal_tanda_tangan']);
        });
    }
};
