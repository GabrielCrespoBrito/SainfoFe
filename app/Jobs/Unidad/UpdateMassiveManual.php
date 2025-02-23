<?php

namespace App\Jobs\Unidad;

use App\M;
use App\Unidad;
use Illuminate\Support\Facades\DB;

class UpdateMassiveManual
{
  protected $campo;
  protected $data;
  protected $value;
  protected $empresa;
  protected $empcodi;
  protected $productsUpdates = [];

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($data)
  {
    $this->data = collect($data);
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
        ->select([
          'unidad.Id',
          'Unicodi',
          'UniPUCD',
          'UniPUCS',
          'UniMarg',
          'UNIPUVD',
          'UNIPUVS',
          'UniPMVS',
          'UniPMVD',
          'porc_com_vend'
        ]);

      $unicodis = $this->data->pluck('Unicodi')->toArray();

      $busqueda->whereIn('unidad.Unicodi',  $unicodis);
    });


    return $busqueda;
  }

  public function updateProducto($unidad)
  {

    if (key_exists($unidad['id'], $this->productsUpdates)) {

      $productData = &$this->productsUpdates[$unidad['id']];

      if ($productData['update'] == false) {
        if ($unidad['Unicodi'] == $productData['unidad_principal_id']) {
          $this->productsUpdates[$unidad['id']]['update'] = true;
          $this->setUpdateProducto($unidad);
        }
      }
    } else {
      $unidad_principal = Unidad::where('Id', $unidad['id'])->first()->Unicodi;

      $isUnidadPrincpal = $unidad_principal == $unidad['Unicodi'];

      $this->productsUpdates[$unidad['id']] = [
        'update' => $isUnidadPrincpal,
        'unidad_principal_id' => $unidad_principal
      ];

      if ($isUnidadPrincpal) {
        $this->setUpdateProducto($unidad);
      }
    }
  }


  public function setUpdateProducto($unidad)
  {
    $auditValues = auditValues();

    DB::connection('tenant')->table('productos')
      ->where('ID', $unidad['id'])
      ->update([
        "ProPUCD" => $unidad['UniPUCD'],
        "ProPUCS" => $unidad['UniPUCS'],
        "ProMarg" => $unidad['UniMarg'],
        "ProPUVD" => $unidad['UNIPUVD'],
        "ProPUVS" => $unidad['UNIPUVS'],
        "ProPMVS" => $unidad['UNIPMVS'],
        "ProPMVD" => $unidad['UNIPMVD'],
        'porc_com_vend' => $unidad['porc_com_vend'],
        "User_Modi" => $auditValues->user,
        "User_FModi" => $auditValues->fecha,
        "User_EModi" => $auditValues->equipo,
      ]);
  }




  /**
   * Execute the job.
   *
   * @return $this
   */
  public function updatePrices()
  {
    $unidades = $this->data;
    $auditValues = auditValues();

    foreach ($unidades as $unidad) {

      DB::connection('tenant')->table('unidad')
        ->where('Unicodi', $unidad['Unicodi'])
        ->update([
          "UniPUCD" => $unidad['UniPUCD'],
          "UniPUCS" => $unidad['UniPUCS'],
          "UniMarg" => $unidad['UniMarg'],
          "UNIPUVD" => $unidad['UNIPUVD'],
          "UNIPUVS" => $unidad['UNIPUVS'],
          "UniPMVS" => $unidad['UNIPMVS'],
          "UniPMVD" => $unidad['UNIPMVD'],
          'porc_com_vend' => $unidad['porc_com_vend'],
          "User_Modi" => $auditValues->user,
          "User_FModi" => $auditValues->fecha,
          "User_EModi" => $auditValues->equipo,
        ]);

      $this->updateProducto($unidad);
    }
  }


  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    return $this->updatePrices();
  }
}
