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

        /**** */

        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Administración de empresas'],
            ['nombre' => 'Administración de empresas', 'external_id' => 20, 'created_at' => $current_date, 'updated_at' => $current_date],
        );
        
        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Ingeniería de sistemas'],
            ['nombre' => 'Ingeniería de sistemas', 'external_id' => 24, 'created_at' => $current_date, 'updated_at' => $current_date],
        );
        
        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Derecho'],
            ['nombre' => 'Derecho', 'external_id' => 22, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Psicología'],
            ['nombre' => 'Psicología', 'external_id' => 25, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Comunicación Estratégica, Multimedia y Digital'],
            ['nombre' => 'Comunicación Estratégica, Multimedia y Digital', 'external_id' => 21, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Ciencias y Artes Audiovisuales'],
            ['nombre' => 'Ciencias y Artes Audiovisuales', 'external_id' => 27, 'created_at' => $current_date, 'updated_at' => $current_date],
        );


        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Ingeniería Comercial'],
            ['nombre' => 'Ingeniería Comercial', 'external_id' => 23, 'created_at' => $current_date, 'updated_at' => $current_date],
        );


        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Ingeniería Financiera'],
            ['nombre' => 'Ingeniería Financiera', 'external_id' => 30, 'created_at' => $current_date, 'updated_at' => $current_date],
        );


        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Administración y Gestión de Hotelería y Turismo'],
            ['nombre' => 'Administración y Gestión de Hotelería y Turismo', 'external_id' => 26, 'created_at' => $current_date, 'updated_at' => $current_date],
        );


        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Contaduría pública'],
            ['nombre' => 'Contaduría pública', 'external_id' => 28, 'created_at' => $current_date, 'updated_at' => $current_date],
        );

        DB::table('carreras')->updateOrInsert(
            ['nombre' => 'Negocios internacionales'],
            ['nombre' => 'Negocios internacionales', 'external_id' => 29, 'created_at' => $current_date, 'updated_at' => $current_date],
        );
    }
}
