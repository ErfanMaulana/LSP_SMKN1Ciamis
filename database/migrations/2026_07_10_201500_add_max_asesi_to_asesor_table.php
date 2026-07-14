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
        Schema::table('asesor', function (Blueprint $table) {
            $table->integer('max_asesi')->nullable()->after('no_met');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->dropColumn('max_asesi');
        });
    }
};
