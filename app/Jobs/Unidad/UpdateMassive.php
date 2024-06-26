<?php

namespace App\Jobs\Unidad;

use App\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMassive
{
  protected $tipo;
  protected $campo;
  protected $value;
  protected $lista_id;
  protected $grupo_id;
  protected $familia_id;
  protected $marca_id;
  protected $local_id;
  protected $productsUpdates = [];
  protected $empresa;
  protected $empcodi;
  protected $decimales;
  
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($tipo, $campo, $value, $lista_id, $grupo_id, $familia_id, $marca_id, $local_id)
  {
    $this->tipo = $tipo;
    $this->campo = $campo;
    $this->value = $value;
    $this->lista_id = $lista_id;
    $this->grupo_id = $grupo_id;
    $this->familia_id = $familia_id;
    $this->marca_id = $marca_id;
    $this->local_id = $local_id;
    $this->decimales = getEmpresaDecimals();
  }

  public function getQuery()
  {
    $busqueda = null;

    DB::connection('tenant')->transaction(function () use (&$busqueda) {

      $busqueda = DB::connection('tenant')->table('unidad')
        ->join('productos', function ($join) {
          $join
            ->on('productos.ID', '=', 'unidad.Id')
            ->on('productos.empcodi', '=', 'unidad.empcodi');
        })
        ->join('lista_precio', function ($join) {
          $join
            ->on('lista_precio.LisCodi', '=', 'unidad.LisCodi');
        })
        ->where('lista_precio.LocCodi', '=', $this->local_id)
        ->where('productos.grucodi', '=', $this->grupo_id)
        ->select([
          'unidad.Id',
          'Unicodi',
          'UniPUCD',
          'UniPUCS',
          'UniMarg',
          'UNIPUVD',
          'UNIPUVS',
          'UniPMVD',
          'UniPMVS',
        ]);


      // Filtrar por lista
      if ($this->lista_id) {
        $busqueda->where('unidad.LisCodi', '=', $this->lista_id);
      }

      if ($this->familia_id) {
        $busqueda->where('productos.famcodi', '=', $this->familia_id);
      }

      if ($this->marca_id) {
        $busqueda->where('productos.marcodi', '=', $this->marca_id);
      }

      $busqueda = $busqueda
        ->get()
        ->chunk(250);
    });


    return $busqueda;
  }



  /**
   * Execute the job.
   *
   * @return $this
   */
  public function updatePrices()
  {

    $busqueda = $this->getQuery();
    foreach ($busqueda as $unidades) {
      foreach ($unidades as $unidad) {
        $result = Unidad::calculateValores(
          $this->tipo,
          $this->campo,
          $this->value,
          $unidad->UniPUCD,
          $unidad->UniPUCS,
          $unidad->UNIPUVD,
          $unidad->UNIPUVS,
          $unidad->UniMarg
        );

        $result->precio_dolares = decimal($result->precio_dolares, $this->decimales->dolares);
        $result->precio_soles = decimal($result->precio_soles, $this->decimales->soles);
          
        DB::connection('tenant')->table('unidad')
          ->where('Unicodi', $unidad->Unicodi)
          ->update([
            "UniPUCD" => $result->costo_dolares,
            "UniPUCS" => $result->costo_soles,
            "UniMarg" => $result->margen,
            "UNIPUVD" => $result->precio_dolares,
            "UNIPUVS" => $result->precio_soles,
          ]);

        $this->updateProducto($unidad, $result);

      }
    }
  }


  /**
   * Execute the job.
   *
   * @return $this
   */
  public function updatePrecesMin()
  {

    $busqueda = $this->getQuery();
    foreach ($busqueda as $unidades) {
      foreach ($unidades as $unidad) {

        $result = Unidad::calculatePrecMinValores(
          $this->tipo,
          $this->value,
          $unidad->UniPMVD,
          $unidad->UniPMVS
        );


        DB::connection('tenant')->table('unidad')
        ->where('Unicodi', $unidad->Unicodi)
        ->update([
          "UniPMVD" => $result->precio_dolares,
          "UniPMVS" => $result->precio_soles,
        ]);

        $this->updateProducto($unidad, $result);
      }
    }
  }

  public function saveUpdate($unidad, $result)
  {
    $data = [];

    if ($this->campo == "precios_min") {
      
      $data = [
        "ProPMVS" => $result->precio_soles,
        "ProPMVD" => $result->precio_dolares,
      ];

    } 
    
    else {
      $data = [
        "ProPUCD" => $result->costo_dolares,
        "ProPUCS" => $result->costo_soles,
        "ProMarg" => $result->margen,
        "ProPUVD" => $result->precio_dolares,
        "ProPUVS" => $result->precio_soles,
      ];
    }

    DB::connection('tenant')->table('productos')
      ->where('ID', $unidad->Id)
      ->update($data);
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

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if ($this->campo == "precios_min") {
      return $this->updatePrecesMin();
    }

    return $this->updatePrices();
  }
}
