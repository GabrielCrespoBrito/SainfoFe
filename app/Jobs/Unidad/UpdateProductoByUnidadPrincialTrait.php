<?php
namespace App\Jobs\Unidad;

use App\Unidad;
use Illuminate\Support\Facades\DB;


trait UpdateProductoByUnidadPrincialTrait {

  public $productsUpdates = [];

  public function saveUpdate($unidad, $dataUpdate)
  {
    DB::connection('tenant')->table('productos')
      ->where('ID', $unidad->Id)
      ->update($dataUpdate);
  }

  public function updateProducto($unidad, $dataUpdate)
  {

    if (key_exists($unidad->Id, $this->productsUpdates)) {

      $productData = &$this->productsUpdates[$unidad->Id];

      if ($productData['update'] == false) {
        if ($unidad['Unicodi'] == $productData['unidad_principal_id']) {
          $this->productsUpdates[$unidad->Id]['update'] = true;
          $this->saveUpdate($unidad, $dataUpdate);
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
        $this->saveUpdate($unidad, $dataUpdate);
      }
    }
  }

}