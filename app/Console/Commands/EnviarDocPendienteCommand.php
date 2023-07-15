<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Admin\SystemStat\SystemStat;
use App\Jobs\Admin\UpdateEmpresasGuiasPendientes;
use App\Jobs\Admin\UpdateEmpresasVentasPendientes;

class EnviarDocPendienteCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:enviar_doc_pendientes {notificar=1}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Enviar documentos pendientes';


  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $this->notifyToAdmin = (bool) $this->argument('notificar');
    $this->ventas();
    $this->guias();
  }

  public function ventas()
  {
    $empresas_pendientes = (new SystemStat)->getEmpresasVentasPendientes();
    $empresas = $empresas_pendientes->data->empresas;

    foreach ($empresas as $empcodi => $empresa) {
      $empresa = Empresa::find($empcodi);
      $empresa->sendPendientes();
    }

    (new UpdateEmpresasVentasPendientes(true, true, $this->notifyToAdmin))->handle();
  }

  public function guias()
  {
    $empresas_pendientes = (new SystemStat)->getEmpresasGuiasPendientes();
    $empresas = $empresas_pendientes->data->empresas;

    foreach ($empresas as $empcodi => $empresa) {
      $empresa = Empresa::find($empcodi);
      $empresa->sendGuiasPendientes();
    }
    
    (new UpdateEmpresasGuiasPendientes(true, true, $this->notifyToAdmin));
  }
}