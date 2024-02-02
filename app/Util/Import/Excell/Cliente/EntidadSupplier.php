<?php

namespace App\Util\Import\Excell\Producto;

class EntidadSupplier
{
  protected $unidadSupplier;
  protected $marcaSupplier;
  protected $dataProduct;

  public function __construct()
  {
    $this->unidadSupplier = new UnidadSupplier();
    $this->marcaSupplier = new MarcaSupplier();
    $this->tipoExistenciaSupplier = new TipoExistenciaSupplier();
    $this->baseIGVSupplier = new BaseIGVSupplier();
    $this->incluyeIGVSupplier = new IncluyeIGVSupplier();
    $this->categoriaSupplier = new CategoriaSupplier();
    $this->codigoSupplier = new CodigoSupplier();
    $this->othersSupplier = new OthersSupplier();
  }

  public function setDataProduct($dataProduct)
  {
    $this->dataProduct = $dataProduct;
  }

  public function handle( &$dataProcess )
  {
    $this->unidadSupplier
    ->setDataProcess( $this->dataProduct['unidad'], 'unidad')
    ->handle($dataProcess);

    $this->marcaSupplier
    ->setDataProcess($this->dataProduct['marca'], 'marca')
    ->handle($dataProcess);

    $this->tipoExistenciaSupplier
      ->setDataProcess($this->dataProduct['tipo_existencia'], 'tipo_existencia')
      ->handle($dataProcess);

    $this->baseIGVSupplier
      ->setDataProcess($this->dataProduct['base_igv'], 'base_igv')
      ->handle($dataProcess);

    $this->incluyeIGVSupplier
      ->setDataProcess($this->dataProduct['incluye_igv'], 'incluye_igv')
      ->handle($dataProcess);

    $this->categoriaSupplier
      ->setDataProcess($this->dataProduct['categoria'])
      ->handle($dataProcess);

    $this->codigoSupplier
      ->setDataProcess(removeComillaToString($this->dataProduct['codigo_unico']), 'codigo_unico')
      ->handle($dataProcess);

    $this->othersSupplier
      ->setDataProcess($this->dataProduct['moneda'])
      ->handle($dataProcess);      
  }
}