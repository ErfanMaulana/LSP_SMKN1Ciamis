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
        Schema::table('jurusan', function (Blueprint $table) {
            if (!Schema::hasColumn('jurusan', 'kode_jurusan')) {
                $table->string('kode_jurusan')->nullable()->after('nama_jurusan');
            }
            if (!Schema::hasColumn('jurusan', 'visi')) {
                $table->text('visi')->nullable();
            }
            if (!Schema::hasColumn('jurusan', 'misi')) {
                $table->text('misi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->dropColumn(['kode_jurusan', 'visi', 'misi']);
        });
    }
};
