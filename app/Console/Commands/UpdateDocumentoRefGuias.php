<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;

class UpdateDocumentoRefGuias extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:update_docref_guias';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Actualizar información del doc de referencia a las guias';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();

    ini_set('max_execution_time', 300);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $empresas = Empresa::activas()->ambienteProduccion()->get();

    foreach ($empresas as $empresa) {
      empresa_bd_tenant($empresa->empcodi);
      $guias_chunk = $empresa->guias->chunk(100);
      foreach ($guias_chunk as $guias) {
        foreach ($guias as $guia) {
          if ($guia->hasDocAsoc()) {
            $guia->updateDocRefFromDocAsoc();
          }
        }
      }
    }
  }
}
