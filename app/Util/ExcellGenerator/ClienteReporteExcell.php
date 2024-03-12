<?php

namespace App\Util\ExcellGenerator;

use App\Util\ExcellGenerator\ExcellGenerator;

class ClienteReporteExcell extends ExcellGenerator
{
  use LineTrait;
  protected $sheet;
  public $linea = 1;


  const HEADER = [
    "#", 
    'Nombre', 
    'Tipo Documento', 
    "Documento", 
    "Dirección", 
    "Ubigeo", 
    "Telefono", 
    "Correo"
  ];

  const TITLE_HOJA = "REPORTE DE CLIENTES";

  public function __construct( $data )
  {
    parent::__construct($data, true, null);
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return self::TITLE_HOJA;
  }

  public function headerSheet()
  {
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['titulo']]);
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['empresa_nombre']]);

    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function processClients()
  {

    $entidades = $this->data['entidades'];
    $loop = 1;

    foreach( $entidades as $entidad ){

      $this->sheet->row($this->getLineaAndSum(),  [ 
      $loop++,
      $entidad->nombre,
      nombreDocumentoCliente($entidad->tipo_documento),
      $entidad->documento,
      $entidad->direccion,
      ubigeoNombre($entidad->ubigeo),
      $entidad->telefono,
      $entidad->correo
      ]);
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
      $this->processClients();

    });
  }
}
