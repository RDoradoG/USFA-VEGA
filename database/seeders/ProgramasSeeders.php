<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramasSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_date = date('Y-m-d H:i:s');

        DB::table('programas')->insert([
            ['nombre' => '4800', 'anio' => 2010, 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Plus', 'anio' => 2016, 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Rediseño', 'anio' => 2022, 'activo' => 1, 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

        DB::table('carreras')->insert([
            ['nombre' => 'Ciencias de la Educación', 'activo' => 0, 'created_at' => $current_date, 'updated_at' => $current_date]//12
        ]);

        DB::table('carrera_simbolo')->insert([
            ['simbolo' => 'ADM', 'carrera_id' => 1, 'programa_id' => 1, 'activo' => 0, 'external_id' => 1, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'EDU', 'carrera_id' => 12, 'programa_id' => 1, 'activo' => 0, 'external_id' => 2, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM', 'carrera_id' => 5, 'programa_id' => 1, 'activo' => 0, 'external_id' => 3, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER', 'carrera_id' => 3, 'programa_id' => 1, 'activo' => 0, 'external_id' => 4, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO', 'carrera_id' => 7, 'programa_id' => 1, 'activo' => 0, 'external_id' => 5, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS', 'carrera_id' => 2, 'programa_id' => 1, 'activo' => 0, 'external_id' => 6, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI', 'carrera_id' => 4, 'programa_id' => 1, 'activo' => 0, 'external_id' => 7, 'created_at' => $current_date, 'updated_at' => $current_date],

            ['simbolo' => 'ADM+', 'carrera_id' => 1, 'programa_id' => 2, 'activo' => 0, 'external_id' => 8, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ATH+', 'carrera_id' => 9, 'programa_id' => 2, 'activo' => 0, 'external_id' => 9, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CAV+', 'carrera_id' => 6, 'programa_id' => 2, 'activo' => 0, 'external_id' => 10, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM+', 'carrera_id' => 5, 'programa_id' => 2, 'activo' => 0, 'external_id' => 11, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CTP+', 'carrera_id' => 10, 'programa_id' => 2, 'activo' => 0, 'external_id' => 12, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER+', 'carrera_id' => 3, 'programa_id' => 2, 'activo' => 0, 'external_id' => 13, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO+', 'carrera_id' => 7, 'programa_id' => 2, 'activo' => 0, 'external_id' => 14, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS+', 'carrera_id' => 2, 'programa_id' => 2, 'activo' => 0, 'external_id' => 15, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'INI+', 'carrera_id' => 11, 'programa_id' => 2, 'activo' => 0, 'external_id' => 16, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'IFI+', 'carrera_id' => 8, 'programa_id' => 2, 'activo' => 0, 'external_id' => 17, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI+', 'carrera_id' => 4, 'programa_id' => 2, 'activo' => 0, 'external_id' => 18, 'created_at' => $current_date, 'updated_at' => $current_date],

            ['simbolo' => 'ADM®', 'carrera_id' => 1, 'programa_id' => 3, 'activo' => 1, 'external_id' => 20, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'COM®', 'carrera_id' => 5, 'programa_id' => 3, 'activo' => 1, 'external_id' => 21, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'DER®', 'carrera_id' => 3, 'programa_id' => 3, 'activo' => 1, 'external_id' => 22, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ICO®', 'carrera_id' => 7, 'programa_id' => 3, 'activo' => 1, 'external_id' => 23, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'SIS®', 'carrera_id' => 2, 'programa_id' => 3, 'activo' => 1, 'external_id' => 24, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'PSI®', 'carrera_id' => 4, 'programa_id' => 3, 'activo' => 1, 'external_id' => 25, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'ATH®', 'carrera_id' => 9, 'programa_id' => 3, 'activo' => 1, 'external_id' => 26, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CAV®', 'carrera_id' => 6, 'programa_id' => 3, 'activo' => 1, 'external_id' => 27, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'CTP®', 'carrera_id' => 10, 'programa_id' => 3, 'activo' => 1, 'external_id' => 28, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'INI®', 'carrera_id' => 11, 'programa_id' => 3, 'activo' => 1, 'external_id' => 29, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['simbolo' => 'IFI®', 'carrera_id' => 8, 'programa_id' => 3, 'activo' => 1, 'external_id' => 30, 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);
    }
}
