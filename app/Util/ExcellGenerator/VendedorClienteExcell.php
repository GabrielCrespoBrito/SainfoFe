<?php

namespace App\Util\ExcellGenerator;

class VendedorClienteExcell extends ExcellGenerator
{
  use LineTrait;
    
  protected $sheet;
  const HEADER = [
    "Codigo",
    "Tipo Doc.",
    "Documento",
    "Razon Social",
    "Zona",
    "Vendedor",
  ];

  public $linea = 1;
  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "reporte_vendedor_ventas_productos";

  public function __construct($data, $title_hoja = "hoja_excell")
  {
    parent::__construct($data, true, null);
    $this->title_hoja = $title_hoja;
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function headerSheet()
  {
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['info']['reporte_nombre']]);
    $this->sheet->row($this->getLineaAndSum(2),  [$this->data['info']['empresa_nombre'] . ' ' . $this->data['info']['empresa_ruc']]);


    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });

  }

  public function processClientes()
  {
    $items = $this->data['items'];

    foreach ($items as $itemVendedor) {

      foreach ($itemVendedor as $cliente) {
        
        $this->sheet->row($this->getLineaAndSum(), [
          $cliente->codigo,
          nombreDocumentoCliente($cliente->tipo_documento),
          $cliente->documento,
          $cliente->nombre,
          $cliente->zona,
          $cliente->vendedor,
        ]);
      }

    }
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->sheet = $sheet;
      $this->sheet->setAutoSize(false);
      $this->sheet->setFontFamily('Arial');
      $this->sheet->setFontSize(10);

      $this->headerSheet();
      $this->processClientes();
    });
  }
}
