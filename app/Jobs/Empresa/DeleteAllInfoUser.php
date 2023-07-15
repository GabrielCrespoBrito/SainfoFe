<?php

namespace App\Jobs\Empresa;

use App\BancoEmpresa;
use App\ClienteProveedor;
use App\Grupo;
use App\Marca;
use App\Empresa;
use App\Familia;
use App\Vendedor;
use App\FormaPago;
use App\ListaPrecio;
use App\Local;
use App\Moneda;
use App\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteAllInfoUser implements ShouldQueue
{
  use Dispatchable;

  /**
   * Create a new job instance.
   *
   * @return void
   */

  public function __construct()
  {
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // Eliminar toda la informaciòn que se pudo haber creado a la hora de poner la los datos por defecto
    
    DB::connection('tenant')->table((new FormaPago)->getTable())->truncate();
    DB::connection('tenant')->table((new Vendedor())->getTable())->truncate();
    DB::connection('tenant')->table((new Familia())->getTable())->truncate();
    DB::connection('tenant')->table((new Marca())->getTable())->truncate();
    DB::connection('tenant')->table((new Grupo())->getTable())->truncate();
    DB::connection('tenant')->table((new Moneda())->getTable())->truncate();
    DB::connection('tenant')->table((new ListaPrecio())->getTable())->truncate();
    DB::connection('tenant')->table((new Producto())->getTable())->truncate();
    DB::connection('tenant')->table((new BancoEmpresa())->getTable())->truncate();
    DB::connection('tenant')->table((new ClienteProveedor())->getTable())->truncate();
    DB::connection('tenant')->table((new Local())->getTable())->truncate();
  }
}
