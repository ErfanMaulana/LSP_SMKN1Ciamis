<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->text('tanda_tangan')->nullable()->after('tanda_tangan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }
};
