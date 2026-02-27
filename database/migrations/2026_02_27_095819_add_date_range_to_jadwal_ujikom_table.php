<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            // Add new columns for date range
            $table->date('tanggal_mulai')->nullable()->after('judul_jadwal');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });

        // Copy existing tanggal to tanggal_mulai and tanggal_selesai
        DB::statement('UPDATE jadwal_ujikom SET tanggal_mulai = tanggal, tanggal_selesai = tanggal WHERE tanggal IS NOT NULL');

        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            // Drop old tanggal column
            $table->dropColumn('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            // Re-add tanggal column
            $table->date('tanggal')->nullable()->after('judul_jadwal');
        });

        // Copy tanggal_mulai back to tanggal
        DB::statement('UPDATE jadwal_ujikom SET tanggal = tanggal_mulai WHERE tanggal_mulai IS NOT NULL');

        Schema::table('jadwal_ujikom', function (Blueprint $table) {
            // Drop the range columns
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
