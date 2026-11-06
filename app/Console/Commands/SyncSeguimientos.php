<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Http\Controllers\AbandonoDesercionController;

#[Signature('sync:seguimientos')]
#[Description('Sincroniza los estudiantes, abandonos y deserciones con el sistema de la USFA')]
class SyncSeguimientos extends Command
{
    /**
     * Nombre y argumentos del comando.
     */
    protected $signature = 'sync:seguimientos';

     /**
     * Descripción del comando.
     */
    protected $description = 'Sincroniza los estudiantes, abandonos y deserciones con el sistema de la USFA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $comandos = [
            'sync:estudiantes',
            'sync:abandonos',
            'sync:deserciones',
        ];

        $this->info("Borrando los abandonos y deserciones sin acciones.");

        $abandono_desercion = new AbandonoDesercionController();
        $abandono_desercion->deleteNewFollowUps();

        foreach ($comandos as $comando) {
            $this->info("Ejecutando: {$comando}");

            $resultado = $this->call($comando);

            if ($resultado !== Command::SUCCESS) {
                $this->error("Falló el comando: {$comando}");

                return Command::FAILURE;
            }

            $this->info("Finalizado: {$comando}");
        }

        $this->info('Todas las sincronizaciones finalizaron correctamente.');

        return Command::SUCCESS;
    }
}
