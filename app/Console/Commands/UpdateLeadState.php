<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\ConfigLead;
use App\Models\Lead;

#[Signature('leads:update-lead-state')]
#[Description('Actualiza el estado de los leads en no contactados')]
class UpdateLeadState extends Command
{

    protected $signature = 'leads:update-lead-state';
    protected $description = 'Actualiza el estado de los leads en no contactados'; 

    private const STATE_ID_NO_CONTACT = 2;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = ConfigLead::where('id', 1)->firstOrFail();
        $fechaLimite = Carbon::now()->subDays($config->dias_inactivo);

        $this->info("Fecha limite: {$fechaLimite}");

        $updated = Lead::where(function ($query) use ($fechaLimite) {
            $query->whereNull('ultimo_contacto')
                ->orWhere('ultimo_contacto', '<', $fechaLimite);
        })->where('estado_id', '!=', self::STATE_ID_NO_CONTACT)->update(['estado_id' => self::STATE_ID_NO_CONTACT]);

        $this->info("Leads actualizados: {$updated}");
    }
}

