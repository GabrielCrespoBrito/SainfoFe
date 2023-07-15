<?php

namespace App\Console\Commands;


use App\Empresa;
use App\FormaPago;
use App\TipoPago;
use Illuminate\Console\Command;
use App\Repositories\TipoPagoRepository;
use Illuminate\Support\Facades\Log;

class CrearMedioPagoCreditoPorDefecto extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:crear_medios_pago_credito';

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
    $empresas_group = Empresa::all()->chunk(50);
    foreach ($empresas_group as $empresas) {
      foreach ($empresas as $empresa) {
        try {
          $this->createMedioPagoCredito($empresa->id());
        } catch (\Throwable $th) {
          Log::info(
            sprintf('@ERROR CREAR-MEDIO-PAGO-CREDITO EMPRESA (%) Error (%s)', $empresa->id , $th->getMessage())
          );
        }
      }
    }
  }

  public function createMedioPagoCredito($empcodi)
  {
    empresa_bd_tenant($empcodi);

    if( FormaPago::where('conCodi' , FormaPago::CODIGO_CREDITO_GENERAL)->first() ){
      return;
    }

    // Forma de Pago credito()
    $fpc = new FormaPago;
    $data = [
      'contipo' => FormaPago::TIPO_CREDITO,
      'connomb' => 'CREDITO',
      'system' => 1
    ];

    $data['items'] = [['PgoDias' => 1]];

    $fpc->repository()->create($data, FormaPago::CODIGO_CREDITO_GENERAL );

  }

}
