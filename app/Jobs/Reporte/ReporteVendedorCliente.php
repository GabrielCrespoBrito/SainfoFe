<?php

namespace App\Jobs\Reporte;

use Illuminate\Support\Facades\DB;

class ReporteVendedorCliente
{
  const REPORTE_NOMBRE  = "REPORTE DE VENDEDOR POR CLIENTE-ZONA";

  /**
   * Data del reporte
   */
  protected $data = [];

  protected $vendedor;
  protected $zona;
  protected $cliente;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($vendedor, $zona)
  {
    $this->vendedor = $vendedor;
    $this->zona = $zona;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $query = DB::connection('tenant')->table('prov_clientes')
      ->join('vendedores', function ($join) {
        $join->on('prov_clientes.VenCodi', '=', 'vendedores.Vencodi');
      })
      ->join('zona', function ($join) {
        $join->on('prov_clientes.ZonCodi', '=', 'zona.ZonCodi');
      })
      ->where('prov_clientes.TipCodi', '=', 'C');

    if ($this->vendedor) {
      $query->where('vendedores.Vencodi', $this->vendedor);
    }


    if ($this->zona) {
      $query->where('zona.ZonCodi', $this->zona);
    }

    return $query->select(
      'prov_clientes.PCCOdi as codigo',
      'prov_clientes.TdoCodi as tipo_documento',
      'prov_clientes.PCRucc as documento',
      'prov_clientes.PCNomb as nombre',
      'vendedores.vennomb as vendedor',
      'vendedores.vennomb as vendedor_codigo',
      'zona.ZonNomb as zona',
    )
      ->orderBy('codigo')
      ->get()
      ->groupBy(['vendedor_codigo']);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();

    // Si no existen registros, detener el resto del script
    if ($query->count() == 0) {
      return;
    }

    $this->data = [
      'items' => $query,
      'info' => $this->getInfoReporte()
    ];;
  }

  public function getInfoReporte()
  {
    $empresa = get_empresa();

    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
    ];
  }


  /**
   * Obtener la data del reporte
   * 
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }
}
