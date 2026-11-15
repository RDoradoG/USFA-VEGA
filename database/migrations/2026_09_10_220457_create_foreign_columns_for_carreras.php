<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('carrera_simbolo_id')
                ->nullable()
                ->after('carrera_id')
                ->constrained('carrera_simbolos')
                ->cascadeOnDelete();
        });

        // Check that external_id is unique in carrera_simbolos
        // for the existing leads that need to be migrated.
        $duplicates = DB::table('leads as l')
            ->join('carreras as c', 'c.id', '=', 'l.carrera_id')
            ->join('carrera_simbolos as cs', 'cs.external_id', '=', 'c.external_id')
            ->whereNotNull('l.carrera_id')
            ->select('cs.external_id')
            ->groupBy('cs.external_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicates) {
            throw new RuntimeException(
                'Duplicate external_id values found in carrera_simbolos. Migration cancelled.'
            );
        }

        // Copy the existing relationships.
        DB::statement("
            UPDATE l
            SET l.carrera_simbolo_id = cs.id
            FROM leads AS l
            INNER JOIN carreras AS c
                ON c.id = l.carrera_id
            INNER JOIN carrera_simbolos AS cs
                ON cs.external_id = c.external_id
            WHERE l.carrera_id IS NOT NULL
        ");

        // Validate that every original relationship was migrated.
        $unmigrated = DB::table('leads')
            ->whereNotNull('carrera_id')
            ->whereNull('carrera_simbolo_id')
            ->count();

        if ($unmigrated > 0) {
            throw new RuntimeException(
                "{$unmigrated} leads could not be migrated. Original column preserved."
            );
        }

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['carrera_id']);
            $table->dropColumn('carrera_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {

            // Restore the original column.
            Schema::table('leads', function (Blueprint $table) {
                $table->foreignId('carrera_id')
                    ->nullable()
                    ->after('carrera_simbolo_id')
                    ->constrained('carreras')
                    ->cascadeOnDelete();
            });

            // Restore the original relationships.
            DB::statement("
                UPDATE l
                SET l.carrera_id = c.id
                FROM leads AS l
                INNER JOIN carrera_simbolos AS cs
                    ON cs.id = l.carrera_simbolo_id
                INNER JOIN carreras AS c
                    ON c.external_id = cs.external_id
            ");

            // Validate the rollback.
            $unrestored = DB::table('leads')
                ->whereNotNull('carrera_simbolo_id')
                ->whereNull('carrera_id')
                ->count();

            if ($unrestored > 0) {
                throw new RuntimeException(
                    "{$unrestored} leads could not be restored. Rollback cancelled."
                );
            }

            // Remove the new foreign key and column.
            Schema::table('leads', function (Blueprint $table) {
                $table->dropForeign(['carrera_simbolo_id']);
                $table->dropColumn('carrera_simbolo_id');
            });

        });
    }
};
