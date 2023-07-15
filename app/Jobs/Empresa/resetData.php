<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use App\Helpers\FHelper;
use Illuminate\Support\Facades\DB;

class resetData
{
  public $empresa;

  public function __construct(Empresa $empresa)
  {
    $this->empresa = $empresa;
  }
  // @TODO
  public function handle()
  {
    
    try {
      empresa_bd_tenant($this->empresa->empcodi);

      $this->empresa->resetDocumentos();
      
      DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
      DB::connection('tenant')->table('productos')->update([
        'prosto1' => 0,
        'prosto2' => 0,
        'prosto3' => 0,
        'prosto4' => 0,
        'prosto5' => 0,
        'prosto6' => 0,
        'prosto7' => 0,
        'prosto8' => 0,
        'prosto9' => 0,
        'prosto10' => 0,
      ]);
      DB::connection('tenant')->table('ventas_cab')->truncate();
      DB::connection('tenant')->table('ventas_detalle')->truncate();
      DB::connection('tenant')->table('ventas_pago')->truncate();
      DB::connection('tenant')->table('ventas_creditos')->truncate();
      DB::connection('tenant')->table('ventas_emails')->truncate();
      // ....|Yes|....|No|....
      DB::connection('tenant')->table('caja')->truncate();
      DB::connection('tenant')->table('caja_detalle')->truncate();
      DB::connection('tenant')->table('bancos_cuenta_cte')->truncate();
      // 
      DB::connection('tenant')->table('guias_cab')->truncate();
      DB::connection('tenant')->table('guia_detalle')->truncate();
      DB::connection('tenant')->table('guias_ventas')->truncate();
      // 
      DB::connection('tenant')->table('compras_cab')->truncate();
      DB::connection('tenant')->table('compras_detalle')->truncate();
      DB::connection('tenant')->table('compras_pago')->truncate();
      // 
      DB::connection('tenant')->table('cotizaciones')->truncate();
      DB::connection('tenant')->table('cotizaciones_detalle')->truncate();
      // 
      DB::connection('tenant')->table('productos_det_mat_inventario')->truncate();
      DB::connection('tenant')->table('productos_mat_inventario')->truncate();
      // 
      DB::connection('tenant')->table('ventas_ra_cab')->truncate();
      DB::connection('tenant')->table('ventas_ra_detalle')->truncate();
      // 
      DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
      // 
      $this->empresa->deleteAllFiles();
    } catch (\Throwable $th) {
      throw new \Exception( $th->getMessage(), 1);
    }
  }


}
