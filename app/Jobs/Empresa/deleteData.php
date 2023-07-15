<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class deleteData
{
  public $empresa;
  public $items;

  const TABLES = [
    'ventas' => ["ventas_cab", 'ventas_detalle', 'ventas_pago', "guias_ventas", 'ventas_creditos', 'ventas_emails', 'ventas_ra_cab', 'ventas_ra_cab', 'ventas_ra_detalle'],
    'compras' => ["compras_cab", "compras_detalle", "compras_pago"],
    'cajas' => ["caja", "caja_detalle", "bancos_cuenta_cte"],
    'guias' => ["guias_cab", "guia_detalle"],
    'cotizaciones' => ["cotizaciones", "cotizaciones_detalle"],
    'productos' => ["productos", "productos_det_mat_inventario", "productos_mat_inventario", "unidad"],
    'lista_precio' => ["lista_precio"],
    'ingresos' => ["ingresos"],
    'egresos' => ["egresos"],
    'marcas' => ["marca"],
    'familias' => ["familias"],
    'grupos' => ["grupos"],
  ];


  public function __construct(Empresa $empresa, $items)
  {
    $this->empresa = $empresa;
    $this->items = $items;
  }

  public function handle()
  {
    if (!$this->items) {
      return;
    }


    DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
    foreach ($this->items as $item) {
      $tables = self::TABLES[$item];
      foreach ($tables as $table) {
        try {
          DB::connection('tenant')->table($table)->truncate();
        } catch (\Throwable $th) {
        }
      }
    }
    DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
  }
}
