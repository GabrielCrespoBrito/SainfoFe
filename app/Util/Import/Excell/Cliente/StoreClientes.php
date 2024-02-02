<?php

namespace App\Util\Import\Excell\Cliente;

use App\Zona;
use Throwable;
use App\Unidad;
use App\Producto;
use App\Vendedor;
use App\ClienteProveedor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Util\ExcellGenerator\LineTrait;
use App\Util\Import\Excell\Producto\ResultTrait;
use App\Util\Import\Excell\Cliente\DataClienteProcess;
use App\Util\Import\Excell\Producto\DataProductoProcess;

class StoreClientes
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
  protected $vendedores;
  public $empcodi;
  protected $zonas;
  protected $ClienteDataProcessor;
  


  public function __construct($data)
  {
    $empresa = get_empresa();
    $this->empcodi = $empresa->empcodi;
    $this->data = $data;
    $this->vendedores = $empresa->vendedores->pluck('Vencodi')->toArray();
    $this->zonas = $empresa->zonas()->pluck('ZonCodi', 'ZonNomb')->toArray();


    $this->ClienteDataProcessor = new DataClienteProcess($this->zonas);
  }

  public function setZona(&$cliente)
  {
    $zona = $cliente['zona'];

    if ($zona) {
      $cliente['zona'] = isset($this->zonas[$zona]) ? $this->zonas[$zona] : $this->createZona($zona);
    }
  }

  public function processVendedor($cliente)
  {
    $vencodi = $cliente['cvend'];
    $vennomb = $cliente['vendedor'];
    
    $res = true;
    
    if ($vencodi) {
      if( in_array( $vencodi, $this->vendedores) == false ){
        $vendedorData = [
          'Vencodi' => $vencodi,
          'vennomb' => $vennomb,
          'empcodi' => $this->empcodi,
        ];

        $res = Vendedor::insert($vendedorData);
        $this->vendedores[] = $vencodi;
      } 
    }

    return $res;
  }

  public function createZona($zonaNombre)
  {
    $zona = new Zona;
    $zona->ZonCodi = agregar_ceros(Zona::max('ZonCodi'), 4);
    $zona->ZonNomb = $zonaNombre;
    $zona->save();
    $this->zonas[$zonaNombre] = $zona->ZonCodi;
  }

  public function clienteStore($cliente)
  {
    $this->setZona($cliente);
    
    $venresp = $this->processVendedor($cliente);

    if ($venresp === false) {
      return $this->addError(sprintf("Error Guardado información de vendedor %s", $cliente['cvend']));
    }

    $clienteData = $this->ClienteDataProcessor
      ->setDataOriginal($cliente)
      ->handle()
      ->getDataProcess();

    $clienteData['EmpCodi'] = $this->empcodi;
    $clienteSave = ClienteProveedor::insert($clienteData);

    if ($clienteSave === false) {
      return $this->addError(sprintf("Error Guardado información de producto %s", $clienteData['ProCodi']));
    }

    return true;
  }

  public function storeModels()
  {
    $data_chunk = $this->data->chunk(50);

    foreach ($data_chunk as $clientes) {
      foreach ($clientes as $cliente) {
        // Detener ejecución
        if ($cliente['tipo_cliente'] === null || trim($cliente['tipo_cliente']) == "") {
          return;
        }

        if ($this->clienteStore($cliente) == false) {
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
