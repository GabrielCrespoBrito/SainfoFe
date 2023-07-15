<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AddEmpresaAditionalParametro extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:add_empresas_parametros';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Agregar Parametros de Configuración Nuevos A Las Empresas';

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
    $parametros = config('enums.empresa_parametros');
    foreach ($empresas_group as $empresas) {
      foreach ($empresas as $empresa) {
        empresa_bd_tenant($empresa->id());
        try {
          $empresa->updateDataAdicional($parametros, false);
        } catch (\Throwable $th) {
          Log::info(
            sprintf('@ERROR AddEmpresaAditionalParametro EMPRESA (%) Error (%s)', $empresa->id, $th->getMessage())
          );
        }
      }
    }
  }
}
