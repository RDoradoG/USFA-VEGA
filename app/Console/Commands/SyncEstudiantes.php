<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\EcovirService;

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\CarreraController;

#[Signature('sync:estudiantes')]
#[Description('Sincroniza los estudiantes con el sistema de la USFA')]
class SyncEstudiantes extends Command
{
    /**
     * Nombre y argumentos del comando.
     */
    protected $signature = 'sync:estudiantes';

     /**
     * Descripción del comando.
     */
    protected $description = 'Sincroniza los estudiantes con el sistema de la USFA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncService = new EcovirService();

        $this->info('Iniciando sincronización de estudiantes...');
        $this->info('Obteniendo Estudiantes...');

        $students_response = $syncService->sync(EcovirService::STEUDENTS_KEY);

        if ($students_response['success']) {

            $this->info('Sincronizando estudiantes...');
            
            $controller = new EstudianteController();
            $total = count($students_response['data']);
            $errors = [];

            $this->info('Obteniendo sedes...');
            $sedes = SedeController::getAllMap();

            $this->info('Obteniendo carreras...');
            $carreras = CarreraController::getAllMap();

            $this->info("Total de estudiantes: {$total}");
            $this->output->progressStart($total);

            foreach ($students_response['data'] as $student) {
                $resultSync = $controller->sync($student, $sedes, $carreras);

                if (!$resultSync['success']) $errors[$resultSync['key']] = $resultSync['message'];

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('Sincronización completada.');

            //if (count($errors) > 0) $this->info('Estudiantes con errores: ' . json_encode($errors));

            return Command::SUCCESS;

        } else {

            $this->info('ERROR! Obteniendo Estudiantes...');
            $this->info($students_response['message']);

            return Command::FAILURE;
        }        
    }
}
