<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class reference_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_date = date('Y-m-d H:i:s');

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'La Paz'],
            ['nombre' => 'La Paz', 'external_id' => 1, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'La Paz'],
            ['nombre' => 'La Paz', 'external_id' => 1, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'El Alto'],
            ['nombre' => 'El Alto', 'external_id' => 2, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'Tupiza'],
            ['nombre' => 'Tupiza', 'external_id' => 3, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'Villazón'],
            ['nombre' => 'Villazón', 'external_id' => 4, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('sedes')->updateOrInsert(
            ['nombre' => 'Ejecutivo'],
            ['nombre' => 'Ejecutivo', 'external_id' => 5, 'created_at' => $current_date, 'updated_at' => $current_date],
        );
    }
}
