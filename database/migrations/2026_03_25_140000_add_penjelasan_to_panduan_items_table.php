<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('panduan_items', function (Blueprint $table) {
            $table->longText('penjelasan')->nullable()->after('description');
        });

        DB::table('panduan_items')
            ->whereNull('penjelasan')
            ->update(['penjelasan' => DB::raw('description')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panduan_items', function (Blueprint $table) {
            $table->dropColumn('penjelasan');
        });
    }
};