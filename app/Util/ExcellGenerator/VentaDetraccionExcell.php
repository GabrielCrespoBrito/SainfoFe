<?php

namespace App\Util\ExcellGenerator;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Moneda;

class VentaDetraccionExcell extends ExcellGenerator
{
  const TITLE_HOJA = "REPORTES DETRACCIÓN";

  const HEADER = [
    'Fecha',
    'TD',
    'Serie',
    'Número',
    'RUC',
    'Razon socia',
    'Moneda',
    'Import',
    'Cod Detr.',
    '% Detr.',
    'Import Detr.',
  ];

  public $nombreEmpresa;
  public $linea = 1;
  public $periodo = "";


  public function __construct($data, $nombreEmpresa = '', $periodo = '')
  {
    parent::__construct($data, true, null);
    $this->nombreEmpresa = $nombreEmpresa;
    $this->periodo = $periodo;
  }

  public function sumLinea()
  {
    $this->linea += 1;
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return self::TITLE_HOJA;
  }

  public function getFiltersNames()
  {
    return [
      'Empresa',
      'Reporte',
      'Periodo',
      'Fecha Reporte',
    ];
  }


  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa,
      'REPORTE DE DETRACCIÓN',
      $this->periodo,
      datePeru('Y-m-d H:m:s')
    ];
  }


  public function headerSheet(&$sheet)
  {
    // Cabecera con nombre del periodo, periodo y moneda
    $sheet->row(1, $this->getFiltersNames());
    $sheet->row(1, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });

    $sheet->row(2, $this->getFiltersValues());

    $sheet->row(4, $this->getHeader());
    $sheet->row(4, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });

    $this->linea = 5;
  }


  /**
   * Columna de un documento
   *
   * @return bool
   */
  public function rowDocumento($venta,  &$sheet)
  {
    $total = $venta->VtaDetrTota != "0" ? $venta->VtaDetrTota : (($venta->VtaImpo/100) * $venta->VtaDetrPorc);
    $lineaData = [
				$venta->VtaFvta,
				$venta->TidCodi,
				$venta->VtaSeri,
				$venta->VtaNumee,
				$venta->PCRucc,
        $venta->PCNomb,
				$venta->monabre, 
				$venta->VtaImpo, 
				$venta->VtaDetrCode, 
				$venta->VtaDetrPorc,
        $total
    ];

    $sheet->row($this->linea, $lineaData);
    $this->sumLinea();
  }

  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->headerSheet($sheet);

      // Iterar tipos de documentos
      foreach ($this->data as $doc ) {
          $this->rowDocumento($doc, $sheet);
      }

    });
  }
}
