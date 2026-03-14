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
        Schema::table('skemas', function (Blueprint $table) {
            $table->decimal('kkm', 5, 2)->default(75.00)->after('jenis_skema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skemas', function (Blueprint $table) {
            $table->dropColumn('kkm');
        });
    }
};
