<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->string('no_reg')->nullable()->unique()->after('nama')
                  ->comment('Nomor registrasi untuk login ke panel asesor');
        });
    }

    public function down(): void
    {
        Schema::table('asesor', function (Blueprint $table) {
            $table->dropColumn('no_reg');
        });
    }
};
