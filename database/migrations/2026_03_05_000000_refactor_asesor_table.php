<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop FK for no_mou on asesor
        Schema::table('asesor', function (Blueprint $table) {
            // Drop the FK constraint (may differ per DB; try both)
            try { $table->dropForeign(['no_mou']); } catch (\Throwable $e) {}
        });

        // 2. Drop no_mou column + ID_skema column, rename no_reg → no_met
        Schema::table('asesor', function (Blueprint $table) {
            if (Schema::hasColumn('asesor', 'no_mou')) {
                $table->dropColumn('no_mou');
            }
        });

        Schema::table('asesor', function (Blueprint $table) {
            if (Schema::hasColumn('asesor', 'ID_skema')) {
                $table->dropColumn('ID_skema');
            }
        });

        Schema::table('asesor', function (Blueprint $table) {
            if (Schema::hasColumn('asesor', 'no_reg') && !Schema::hasColumn('asesor', 'no_met')) {
                $table->renameColumn('no_reg', 'no_met');
            }
        });

        // 3. Create asesor_skema pivot table
        if (!Schema::hasTable('asesor_skema')) {
            Schema::create('asesor_skema', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asesor_id');
                $table->unsignedBigInteger('skema_id');
                $table->timestamps();

                $table->unique(['asesor_id', 'skema_id']);
                $table->foreign('asesor_id')->references('ID_asesor')->on('asesor')->onDelete('cascade');
                $table->foreign('skema_id')->references('id')->on('skemas')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('asesor_skema');

        Schema::table('asesor', function (Blueprint $table) {
            if (Schema::hasColumn('asesor', 'no_met') && !Schema::hasColumn('asesor', 'no_reg')) {
                $table->renameColumn('no_met', 'no_reg');
            }
            if (!Schema::hasColumn('asesor', 'ID_skema')) {
                $table->unsignedBigInteger('ID_skema')->nullable()->after('ID_asesor');
            }
            if (!Schema::hasColumn('asesor', 'no_mou')) {
                $table->string('no_mou')->nullable()->after('ID_skema');
            }
        });
    }
};
