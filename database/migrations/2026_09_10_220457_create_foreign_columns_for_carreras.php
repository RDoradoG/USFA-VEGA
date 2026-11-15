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
        $this->programs(date('Y-m-d H:i:s'));

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('carrera_simbolo_id')
                ->nullable()
                ->after('carrera_id')
                ->constrained('carrera_simbolos')
                ->noActionOnDelete();
        });

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

        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }

    private function programs(string $current_date): void
    {

        $programaIda = DB::table('programas')->insertGetId(['nombre' => '4800', 'anio' => 2010, 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date]);
        $programaIdb = DB::table('programas')->insertGetId(['nombre' => 'Plus', 'anio' => 2016, 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date]);
        $programaIdc = DB::table('programas')->insertGetId(['nombre' => 'Rediseño', 'anio' => 2022, 'activo' => 1, 'created_at' => $current_date, 'updated_at' => $current_date]);

        $carreraId = DB::table('carreras')->insertGetId(['nombre' => 'Ciencias de la Educación', 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date]);

        DB::table('carrera_simbolos')->insert([
            ['simbolo' => 'ADM', 'carrera_id' => 1, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 1, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'EDU', 'carrera_id' => $carreraId, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 2, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM', 'carrera_id' => 5, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 3, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER', 'carrera_id' => 3, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 4, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO', 'carrera_id' => 7, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 5, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS', 'carrera_id' => 2, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 6, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI', 'carrera_id' => 4, 'programa_id' => $programaIda, 'activo' => 0, 'external_id' => 7, 'created_at' => $current_date, 'updated_at' => $current_date],

            ['simbolo' => 'ADM+', 'carrera_id' => 1, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 8, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ATH+', 'carrera_id' => 9, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 9, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CAV+', 'carrera_id' => 6, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 10, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM+', 'carrera_id' => 5, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 11, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CTP+', 'carrera_id' => 10, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 12, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER+', 'carrera_id' => 3, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 13, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO+', 'carrera_id' => 7, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 14, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS+', 'carrera_id' => 2, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 15, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'INI+', 'carrera_id' => 11, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 16, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'IFI+', 'carrera_id' => 8, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 17, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI+', 'carrera_id' => 4, 'programa_id' => $programaIdb, 'activo' => 0, 'external_id' => 18, 'created_at' => $current_date, 'updated_at' => $current_date],

            ['simbolo' => 'ADM®', 'carrera_id' => 1, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 20, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM®', 'carrera_id' => 5, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 21, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER®', 'carrera_id' => 3, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 22, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO®', 'carrera_id' => 7, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 23, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS®', 'carrera_id' => 2, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 24, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI®', 'carrera_id' => 4, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 25, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ATH®', 'carrera_id' => 9, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 26, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CAV®', 'carrera_id' => 6, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 27, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CTP®', 'carrera_id' => 10, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 28, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'INI®', 'carrera_id' => 11, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 29, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'IFI®', 'carrera_id' => 8, 'programa_id' => $programaIdc, 'activo' => 1, 'external_id' => 30, 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {

            Schema::table('carreras', function (Blueprint $table) {
                $table->string('external_id')->nullable()->after('activo');
            });

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
