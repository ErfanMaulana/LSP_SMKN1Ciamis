<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jadwal_kelompok')) {
            return;
        }

        Schema::create('jadwal_kelompok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('kelompok_id');
            $table->timestamps();

            $table->foreign('jadwal_id')->references('id')->on('jadwal_ujikom')->cascadeOnDelete();
            $table->foreign('kelompok_id')->references('id')->on('kelompok')->cascadeOnDelete();

            $table->unique(['jadwal_id', 'kelompok_id']);
            $table->unique('kelompok_id');
        });

        if (!Schema::hasColumn('jadwal_ujikom', 'kelompok_id')) {
            return;
        }

        $legacyRows = DB::table('jadwal_ujikom')
            ->whereNotNull('kelompok_id')
            ->selectRaw('MIN(id) as jadwal_id, kelompok_id')
            ->groupBy('kelompok_id')
            ->get();

        if ($legacyRows->isEmpty()) {
            return;
        }

        $now = now();
        $insertRows = $legacyRows->map(function ($row) use ($now) {
            return [
                'jadwal_id' => $row->jadwal_id,
                'kelompok_id' => $row->kelompok_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        DB::table('jadwal_kelompok')->insert($insertRows);
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kelompok');
    }
};
