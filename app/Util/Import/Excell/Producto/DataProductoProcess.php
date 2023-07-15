<?php

namespace App\Util\Import\Excell\Producto;


class DataProductoProcess 
{
  const HEADERS = [
    "id" => 'ID',
    "codigo_unico" => 'ProCodi',
    "codigo_barra" => 'ProCodi1',
    "categoria" => "categoria ",
    "marca" => "marcodi",
    "familia" => "famcodi",
    "grupo" => "grucodi",
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
    "campo_delete" => "UDelete",
    "tipo_existencia" => "tiecodi",
  ];

  protected $dataProcess;
  protected $dataOriginal;
  protected $entidadSupplier;

  public function __construct()
  { 
    $this->entidadSupplier = new EntidadSupplier();
  }

  public function setDataProductoOriginal($dataOriginal)
  {
    $this->dataOriginal = $dataOriginal;
    $this->entidadSupplier->setDataProduct($dataOriginal);
    return $this;
  }

  public function handle()
  {
    $this->prepareProductData();
    return $this;
  }

  public function getValueFromOriginal($campoName)
  {
    return $this->dataOriginal[$campoName];
  }

  public function getHeaderName($campoName)
  {
    return self::getHeader($campoName);
  }

  public static function getHeader($campoName)
  {
    return self::HEADERS[$campoName];
  }

  public function prepareProductData()
  {
    $this->dataProcess[$this->getHeaderName('nombre')] = $this->getValueFromOriginal('nombre');    

    $codigo_barra = trim(removeComillaToString($this->getValueFromOriginal('codigo_barra')));
    $codigo_barra = $codigo_barra ? $codigo_barra : null;
    
    $this->dataProcess[$this->getHeaderName('campo_delete')] = 0;
    $this->dataProcess[$this->getHeaderName('codigo_barra')] = $codigo_barra;
    $this->dataProcess[$this->getHeaderName('costo_soles')] = $this->getValueFromOriginal('costo_soles');
    $this->dataProcess[$this->getHeaderName('costo_dolares') ] = $this->getValueFromOriginal('costo_dolares');
    $this->dataProcess[$this->getHeaderName('margen')] = $this->getValueFromOriginal('margen');
    $this->dataProcess[$this->getHeaderName('precio_soles')] = $this->getValueFromOriginal('precio_soles');
    $this->dataProcess[$this->getHeaderName('precio_dolares')] = $this->getValueFromOriginal('precio_dolares');
    $this->dataProcess[$this->getHeaderName('peso')] = $this->getValueFromOriginal('peso');
    $this->entidadSupplier->handle($this->dataProcess);
  }

  public function getDataProcess()
  {
    return $this->dataProcess;
  }
}
