<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Admin\UpdateEmpresasGuiasPendientes;
use App\Jobs\Admin\UpdateEmpresasVentasPendientes;

/**
 * Comando para actualizar las acciones pendientes del sistema, enviar facturas/guias/resumenes pendientes de la sunat, validar ordenes, etc
 * 
 */

class AdminUpdateAccionesPendientes extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:admin_update_acciones_pendientes';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Actualizar las acciones pendientes del sistema';

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
    (new UpdateEmpresasVentasPendientes)->handle();
    (new UpdateEmpresasGuiasPendientes())->handle();
  }
}
