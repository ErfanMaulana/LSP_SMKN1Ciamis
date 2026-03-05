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
            $table->unsignedBigInteger('ID_asesor')->nullable()->after('ID_jurusan');
            $table->foreign('ID_asesor')->references('ID_asesor')->on('asesor')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropForeign(['ID_asesor']);
            $table->dropColumn('ID_asesor');
        });
    }
};
