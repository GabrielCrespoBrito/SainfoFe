<?php

namespace App\Jobs\Unidad;

use App\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UpdatePrecios
{
  use UpdateProductoByUnidadPrincialTrait;

  protected $tipo_cambio;
  protected $empresa;
  protected $empcodi;
  protected $decimales_soles;
  protected $decimales_dolares;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($tipo_cambio)
  {
    $this->tipo_cambio = $tipo_cambio;
    $this->empresa = get_empresa();
    $this->empcodi = $this->empresa->id();

    $decimales = getEmpresaDecimals();
    $this->decimales_soles = $decimales->soles;
    $this->decimales_dolares = $decimales->dolares;
  }

  /**
   * Execute the job.
   *
   * @return $this
   */
  public function updateTipoCambio()
  {
    $this->empresa->opcion->update(['FE_RPTA' => $this->tipo_cambio]);

    Cache::forget("option_empresa" . empcodi());

    return $this;
  }

  /**
   * Execute the job.
   *
   * @return $this
   */
  public function updatePrices()
  {
    $tipo_cambio = $this->tipo_cambio;

    DB::connection('tenant')->transaction(function () use ($tipo_cambio) {

      $busqueda = DB::connection('tenant')->table('unidad')
        ->join('productos', function ($join) {
          $join
            ->on('productos.ID', '=', 'unidad.Id')
            ->on('productos.empcodi', '=', 'unidad.empcodi');
        })
        ->where('productos.moncodi', '=', "02")
        ->select([
          'unidad.Unicodi',
          'unidad.Id',
          'Unicodi',
          'UniPUCD',
          'UniPUCS',
          'UniMarg',
          'UNIPUVD',
          'UNIPUVS',
          'UniPMVD',
        ])
        ->get()
        ->chunk(250);


      foreach ($busqueda as $unidades) {

        foreach ($unidades as $unidad) {

          $result = Unidad::calculatePrecioCostoByNewTipoCambio($tipo_cambio, $unidad->UniPUCD, $unidad->UNIPUVD, $unidad->UniPMVD);

          $result->precio_soles = math()->addDecimal($result->precio_soles, $this->decimales_soles);
          $result->precio_min_soles = math()->addDecimal($result->precio_min_soles, $this->decimales_soles);

          DB::connection('tenant')->table('unidad')
            ->where('Unicodi', $unidad->Unicodi)
            ->update([
              "UniPUCS" => $result->costo_soles,
              "UNIPUVS" => $result->precio_soles,
              "UniPMVS" => $result->precio_min_soles,
            ]);

          $this->updateProducto($unidad, $result);
        }
      }
      // second foreach 
    });
  }

  public function saveUpdate($unidad, $result)
  {
    DB::connection('tenant')->table('productos')
      ->where('ID', $unidad->Id)
      ->update([
        "ProPUCS" => $result->costo_soles,
        "ProPUVS" => $result->precio_soles,
        "ProPMVS" => $result->precio_min_soles,
      ]);
  }

  public function updateProducto($unidad, $result)
  {

    if (key_exists($unidad->Id, $this->productsUpdates)) {

      $productData = &$this->productsUpdates[$unidad->Id];

      if ($productData['update'] == false) {
        if ($unidad['Unicodi'] == $productData['unidad_principal_id']) {
          $this->productsUpdates[$unidad->Id]['update'] = true;
          $this->saveUpdate($unidad, $result);
        }
      }
    } else {
      $unidad_principal = Unidad::where('Id', $unidad->Id)->first()->Unicodi;
      $isUnidadPrincpal = $unidad_principal == $unidad->Unicodi;
      $this->productsUpdates[$unidad->Id] = [
        'update' => $isUnidadPrincpal,
        'unidad_principal_id' => $unidad_principal
      ];

      if ($isUnidadPrincpal) {
        $this->saveUpdate($unidad, $result);
      }
    }
  }

  public function handle()
  {
    return $this
      ->updateTipoCambio()
      ->updatePrices();
  }
}
