<?php

namespace App\Util\ExcellGenerator;

class FacturacionElectronicaExcell extends ExcellGenerator
{
  const HEADER = [
    "Nro Oper", 'Tipo Doc.', "Serie", "Numero", "Fecha Doc", "Ruc", "Razon Social", "Estado Sunat", "Descripcion"
  ];

  public $fechaInicio;
  public $fechaFinal;
  public $tipoDocs;
  public $estadoSunat;

  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";

  public function __construct( $data, $fechaInicio, $fechaFinal, $tipoDocs, $estadoSunat )
  {
    parent::__construct($data, true, null);
    $this->fechaInicio = $fechaInicio;
    $this->fechaFinal = $fechaFinal;
    $this->tipoDocs = $tipoDocs;
    $this->estadoSunat = $estadoSunat;
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
      'Fecha Inicio',
      'Fecha Final',
      'Tipo de Documentos',
      'Estado Sunat',
    ];    
  }


  public function getFiltersValues()
  {
    return [
      $this->fechaInicio,
      $this->fechaFinal,
      $this->tipoDocs,
      $this->estadoSunat,
    ];
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

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

      foreach ($this->data as $index => $data) {
        $data = [
          $data->VtaOper, 
          $data->TidCodi,
          $data->VtaSeri, 
          $data->VtaNumee, 
          $data->VtaFvta, 
          $data->cliente_with->PCRucc, 
          $data->cliente_with->PCNomb, 
          $data->statusSunat->status_code, 
          $data->statusSunat->status_message, 
        ];
        $sheet->row($index+5, $data);
      }      
    });
  }
}
