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
        if (Schema::hasColumn('rekaman_asesmen_kompetensi', 'catatan_footer')) {
            Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
                $table->dropColumn('catatan_footer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('rekaman_asesmen_kompetensi', 'catatan_footer')) {
            Schema::table('rekaman_asesmen_kompetensi', function (Blueprint $table) {
                $table->string('catatan_footer')->nullable()->default('* Coret yang tidak perlu');
            });
        }
    }
};