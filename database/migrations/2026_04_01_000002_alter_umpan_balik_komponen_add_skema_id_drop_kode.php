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
        Schema::table('umpan_balik_komponen', function (Blueprint $table) {
            $table->foreignId('skema_id')->nullable()->after('id')->constrained('skemas')->nullOnDelete();
        });

        Schema::table('umpan_balik_komponen', function (Blueprint $table) {
            $table->dropColumn('kode_komponen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umpan_balik_komponen', function (Blueprint $table) {
            $table->string('kode_komponen', 30)->nullable()->after('id');
        });

        Schema::table('umpan_balik_komponen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('skema_id');
        });
    }
};
