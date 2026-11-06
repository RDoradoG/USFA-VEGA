<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudiantesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$current_date = date('Y-m-d H:i:s');

        DB::table('seguimiento_estados')->insert([
            ['nombre' => 'Nuevo', 'color' => 'grey', 'orden' => 1],
            ['nombre' => 'Sin contactar', 'color' => 'orange', 'orden' => 2],
            ['nombre' => 'En Proceso', 'color' => 'yellow', 'orden' => 3],
            ['nombre' => 'Recuperado', 'color' => 'green', 'orden' => 4],
            ['nombre' => 'Perdido', 'color' => 'red', 'orden' => 5]
        ]);

        DB::table('seguimiento_tipos')->insert([
            ['nombre' => 'Abandono'],
            ['nombre' => 'Deserción']
        ]);

        DB::table('desercion_tipos')->insert([
            ['nombre' => 'Sin Asignación'],
            ['nombre' => 'Sin Nota'],
            ['nombre' => 'Nota']
        ]);
    }
}
