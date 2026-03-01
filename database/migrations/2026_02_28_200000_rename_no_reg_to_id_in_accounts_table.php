<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rename no_reg → id (string primary key) di tabel accounts.
     * Kolom auto-increment id lama dihapus.
     */
    public function up(): void
    {
        // 1. Hapus auto-increment id
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // 2. Rename no_reg → id
        Schema::table('accounts', function (Blueprint $table) {
            $table->renameColumn('no_reg', 'id');
        });

        // 3. Jadikan id sebagai primary key
        Schema::table('accounts', function (Blueprint $table) {
            $table->primary('id');
        });
    }

    /**
     * Reverse: kembalikan ke auto-increment id + no_reg string
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->renameColumn('id', 'no_reg');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->id()->first();
            $table->unique('no_reg');
        });
    }
};
