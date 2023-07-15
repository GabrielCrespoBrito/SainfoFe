<?php

namespace App\Util\ExcellGenerator;

use App\ModuloMonitoreo\StatusCode\StatusCode;

class GuiaElectronicaExcell extends ExcellGenerator
{
  const HEADER = [
    "Nro Oper", "Serie", "Numero", "Fecha Doc", "Ruc", "Razon Social", "Estado Sunat", "Descripcion"
  ];


  // public $estados_sunat = [
  //   '0001' => 'El comprobante existe y está aceptado',
  //   '0002' => 'El comprobante existe  pero está rechazado',
  //   '0003' => 'El comprobante existe pero está de baja',
  //   '0011' => 'El comprobante de pago electrónico no existe',
  // ];

  public $fechaInicio;
  public $fechaFinal;
  public $estadoSunat;

  const TITLE_HOJA = "REPORTE GUIAS ELECTRONICA";

  public function __construct($data, $fechaInicio, $fechaFinal, $estadoSunat)
  {
    parent::__construct($data, true, null);
    $this->fechaInicio = $fechaInicio;
    $this->fechaFinal = $fechaFinal;
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
      'Estado sunat',
      
    ];
  }

  public function getFiltersValues()
  {
    return [
      $this->fechaInicio,
      $this->fechaFinal,
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

        if( $data->GuiEsta == "A" ){
          $code = "0003";
        }
        else {
          switch ($data->fe_rpta) {
            case 0:
            $code = "0001";
            break;
            case 9:
            case "9":
            $code = "0011";
            break;
            default:
            $code = "0002";
            break;
          }
        }

        $message = StatusCode::CODES[$code];

        // ----------
        $data = [
          $data->GuiOper,
          $data->GuiSeri,
          $data->GuiNumee,
          $data->GuiFemi,
          $data->PCRucc,
          $data->PCNomb,
          $code,
          $message,
        ];
        $sheet->row($index + 5, $data);
      }
    });
  }
}