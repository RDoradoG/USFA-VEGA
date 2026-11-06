<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Services\EcovirService;

use App\Http\Controllers\SedeController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\AbandonoDesercionController;
use App\Http\Controllers\DesercionTipoController;

#[Signature('sync:deserciones')]
#[Description('Sincroniza las deserciones de los estudiantes con el sistema de la USFA')]
class SyncDeserciones extends Command
{
    /**
     * Nombre y argumentos del comando.
     */
    protected $signature = 'sync:deserciones';

     /**
     * Descripción del comando.
     */
    protected $description = 'Sincroniza las deserciones de los estudiantes con el sistema de la USFA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncService = new EcovirService();

        $this->info('Iniciando sincronización de deserciones...');
        $this->info('Obteniendo Deserciones...');

        $attrition_response = $syncService->sync(EcovirService::ATTRITION_KEY);

        if ($attrition_response['success']) {

            $this->info('Sincronizando deserciones... ');

            $controller = new AbandonoDesercionController();
            $total = count($attrition_response['data']);
            $errors = [];

            $this->info('Obteniendo sedes...');
            $sedes = SedeController::getAllMap();

            $this->info('Obteniendo carreras...');
            $carreras = CarreraController::getAllMap();

            $this->info('Obteniendo tipos de deserciones...');
            $attritionTypes = DesercionTipoController::getAllMap();

            $this->info("Total de deserciones: {$total}");
            $this->output->progressStart($total);

            foreach ($attrition_response['data'] as $dropoff) {
                $resultSync = $controller->sync($dropoff, $sedes, $carreras, $attritionTypes, true);

                if (!$resultSync['success']) $errors[$resultSync['key']] = $resultSync['message'];

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('Sincronización completada.');

            //if (count($errors) > 0) $this->info('Abandonos con errores: ' . json_encode($errors));

            return Command::SUCCESS;
        } else {

            $this->info('ERROR! Obteniendo Abandonos...');
            $this->info($attrition_response['message']);

            return Command::FAILURE;
        }  
            
    }
}
