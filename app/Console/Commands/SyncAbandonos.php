<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Services\EcovirService;

use App\Http\Controllers\SedeController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\AbandonoDesercionController;

#[Signature('sync:abandonos')]
#[Description('Sincroniza los abandonos de los estudiantes con el sistema de la USFA')]
class SyncAbandonos extends Command
{

    /**
     * Nombre y argumentos del comando.
     */
    protected $signature = 'sync:abandonos';

     /**
     * Descripción del comando.
     */
    protected $description = 'Sincroniza los abandonos de los estudiantes con el sistema de la USFA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncService = new EcovirService();

        $this->info('Iniciando sincronización de abandonos...');
        $this->info('Obteniendo Abandonos...');

        $dropout_response = $syncService->sync(EcovirService::DROPOUT_KEY);

        if ($dropout_response['success']) {

            $this->info('Sincronizando abandonos... ');

            $controller = new AbandonoDesercionController();
            $total = count($dropout_response['data']);
            $errors = [];

            $this->info('Obteniendo sedes...');
            $sedes = SedeController::getAllMap();

            $this->info('Obteniendo carreras...');
            $carreras = CarreraController::getAllMap();

            $this->info("Total de Abandonos: {$total}");
            $this->output->progressStart($total);

            foreach ($dropout_response['data'] as $dropout) {
                $resultSync = $controller->sync($dropout, $sedes, $carreras);

                if (!$resultSync['success']) $errors[$resultSync['key']] = $resultSync['message'];

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('Sincronización completada.');

            //if (count($errors) > 0) $this->info('Abandonos con errores: ' . json_encode($errors));

            return Command::SUCCESS;
        } else {

            $this->info('ERROR! Obteniendo Abandonos...');
            $this->info($dropout_response['message']);

            return Command::FAILURE;
        }  
            
    }
}
