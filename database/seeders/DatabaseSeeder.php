<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $current_date = date('Y-m-d H:i:s');

        DB::table('usuarios')->insert([
            'nombre' => 'Admin',
            'email' => 'admin@usfa.com',
            'rol' => 'JEFE',
            'password' => Hash::make('Admin123'),
            'created_at' => $current_date,
            'updated_at' => $current_date
        ]);

        DB::table('estados')->insert([
            ['nombre' => 'Nuevo', 'color' => 'gray', 'orden' => 1, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Sin contactar', 'color' => 'orange', 'orden' => 2, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Contactado', 'color' => 'yellow', 'orden' => 3, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Interesado', 'color' => 'cyan', 'orden' => 4, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'En Caja', 'color' => 'emerald', 'orden' => 5, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Inscrito', 'color' => 'green', 'orden' => 6, 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Perdido', 'color' => 'red', 'orden' => 7, 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

        DB::table('horarios')->insert([
            ['nombre' => 'Normal', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Ejecutivo', 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

        DB::table('sedes')->insert([
            ['nombre' => 'La Paz', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'El Alto', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Tupiza', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Villazón', 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

        DB::table('carreras')->insert([
            ['nombre' => 'Administración de empresas', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Ingeniería de sistemas', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Derecho', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Psicología', 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

        DB::table('fuentes')->insert([
            ['nombre' => 'Meta', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Feria', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Web', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Colegio', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Redes Sociales', 'created_at' => $current_date, 'updated_at' => $current_date],
            ['nombre' => 'Referido', 'created_at' => $current_date, 'updated_at' => $current_date]
        ]);

    }
}
