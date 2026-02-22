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
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->string('kewarganegaraan')->nullable()->after('kebangsaan');
            $table->string('pekerjaan')->nullable()->after('pendidikan_terakhir');
            $table->string('nama_lembaga')->nullable()->after('pekerjaan');
            $table->text('alamat_lembaga')->nullable()->after('nama_lembaga');
            $table->string('jabatan')->nullable()->after('alamat_lembaga');
            $table->string('no_fax_lembaga')->nullable()->after('jabatan');
            $table->string('email_lembaga')->nullable()->after('no_fax_lembaga');
            $table->string('unit_lembaga')->nullable()->after('email_lembaga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesi', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_kelamin',
                'kewarganegaraan',
                'pekerjaan',
                'nama_lembaga',
                'alamat_lembaga',
                'jabatan',
                'no_fax_lembaga',
                'email_lembaga',
                'unit_lembaga',
            ]);
        });
    }
};
