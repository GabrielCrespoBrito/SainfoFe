<?php

namespace App\Console\Commands;

use App\Empresa;
use App\TipoPago;
use Illuminate\Console\Command;
use App\Repositories\TipoPagoRepository;
use Illuminate\Support\Facades\Log;

class SincronizarMediosPagos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:sincronizar_medios_pagos {tipo_pago=-1}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar Nuevos Medios de Pagos A las Base de Datos de las Empresas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $tipo_pago = $this->argument('tipo_pago');

      $empresas_group = Empresa::all()->chunk(50);

      $tipos_pagos = $tipo_pago == '-1' ? TipoPago::all() : TipoPago::where('TpgCodi', $tipo_pago)->get();
      foreach ($empresas_group as $empresas) {
        foreach ( $empresas as $empresa ) {
          try {
            $empresa->syncMedioPagos( $tipos_pagos );
          } catch (\Throwable $th) {
          }
        }
      }
    }
}
