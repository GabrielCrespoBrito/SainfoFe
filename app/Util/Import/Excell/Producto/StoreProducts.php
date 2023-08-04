<?php
namespace App\Util\Import\Excell\Producto;

use Exception;
use Throwable;
use App\Unidad;
use App\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Util\ExcellGenerator\LineTrait;
use App\Util\Import\Excell\Producto\ResultTrait;
use App\Util\Import\Excell\Producto\DataProductoProcess;

class StoreProducts
{

  const HEADERS = [
    "id" => 'ID',
    "codigo_unico" => 'ProCodi',
    "codigo_barra" => 'ProCodi1',
    "categoria" => "categoria ",
    "marca" => "as",
    "familia" => "famcodi",
    "grupo" => "grucodi",
    "marcodi" => "grucodi",
    "unidad" => "unpcodi",
    "nombre" => "ProNomb",
    "moneda" => "moncodi",
    "costo_dolares" => 'ProPUCD',
    "costo_soles" => 'ProPUCS',
    "margen" => 'ProMarg',
    "precio_soles" => 'ProPUVS',
    "precio_dolares" => 'ProPUVD',
    "peso" => 'ProPeso',
    "base_igv" => "BaseIGV",
    "incluye_igv" => "incluye_igv",
    "tipo_existencia" => "tiecodi",
  ];

  use
    LineTrait,
    ResultTrait;

  protected $data;
  protected $listas;
  protected $productDataProcessor;

  public function __construct($data)
  {
    $this->data = $data;
    $this->listas = get_empresa()->listas;
    $this->productDataProcessor = new DataProductoProcess();
  }

  public function productoStore($product)
  {
    $productData = $this->productDataProcessor
      ->setDataProductoOriginal($product)
      ->handle()
      ->getDataProcess();
    
    $productoId = Producto::insertGetId($productData);
    
    if($productoId === false){
      return $this->addError(sprintf("Error Guardado información de producto %s", $productData['ProCodi']));
    }

    $isSave = Unidad::createFromProducto($productoId, $productData, $this->listas);

    if ($isSave === false) {
      return $this->addError(sprintf("Error Guardado información de producto (Unidad) %s", $productData['ProCodi']));
    }

    return true;
  }

  public function storeModels()
  {
    $data_chunk = $this->data->chunk(50);

    foreach ($data_chunk as $products) {
      foreach ($products as $product) {
        // Detener ejecución
        if ($product['codigo_unico'] === null || trim($product['codigo_unico']) == "") {
          return;
        }

        if($this->productoStore($product) == false){
          return;
        }
      }
    }
  }

  public function handle()
  {
    try {
      DB::connection('tenant')->beginTransaction();
      $this->storeModels();
      DB::connection('tenant')->commit();
    } catch (Throwable $th) {
      _dd($th);
      exit();
      $this->addError($th->getMessage());
      DB::connection('tenant')->rollback();
    }

    return $this;
  }
}
