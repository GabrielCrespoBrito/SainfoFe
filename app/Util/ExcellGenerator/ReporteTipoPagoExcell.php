<?php

namespace App\Util\ExcellGenerator;

use App\TipoPago;

class ReporteTipoPagoExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER_TABLE = [
    'Nro Op.',
    'F.Pago',
    'Nª Doc',
    'Razòn Social',
    'Voucher',
    'Mon.',
    'T.C',
    'Importe'
  ];


  public $nombreEmpresa;
  public $linea = 1;

  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "REPORTE VENDEDOR-VENTAS";

  public function __construct($data, $title_hoja = "reporte_por_tipo_pago")
  {
    parent::__construct($data, true, null);
    $this->title_hoja = $title_hoja;
  }



  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function headerSheet()
  {
    // -----------------------------------------------------------------------------------------------    
    $this->sheet->row($lineTitulo = $this->getLineaAndSum(),  [$this->data['titulo']]);
    $this->sheet->row($lineEmpresa = $this->getLineaAndSum(),  [$this->data['nombre_empresa']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Caja:', $this->data['caja']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Usuario:', $this->data['usuario']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Apertura:', $this->data['fecha_apertura']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Cierre:', $this->data['fecha_cierre']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Cuenta:', $this->data['cuenta']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Banco:', $this->data['banco']]);
    $this->sheet->row($this->getLineaAndSum(2),  ['Moneda:', $this->data['moneda']]);
    
    $this->sheet->row($lineTitulo, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($lineEmpresa, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function tipoPagoHeader($id)
  {
    $tipo_nombre_pago = TipoPago::find($id)->getNombre();

    $this->sheet->row($line = $this->getLineaAndSum(),  [$tipo_nombre_pago, '','','','','','', '']);

    $this->sheet->row($line ,function($row){
      $row->setFontWeight('bold');
      $row->setBackground('#cccccc');
    });

    return $tipo_nombre_pago;
  }

  public function  getHeaderTable()
  {
    $this->sheet->row($line = $this->getLineaAndSum(),  self::HEADER_TABLE);


    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function tipoPagoTotal($tipoPagoTotal, $total_soles = 0, $total_dolar = 0)
  {
    $this->sheet->row($line = $this->getLineaAndSum(2),  [$tipoPagoTotal , '', '', '' , 'TOTAL S./ - USD./ ', '' , $total_soles, $total_dolar]);    
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#cccccc');
    });    
  }

  public function totalGeneral($total_soles = 0, $total_dolar = 0)
  {
    $this->sheet->row($line = $this->getLineaAndSum(2),  [ 'TOTAL GENERAL S./ - USD./ ', '', '', '', '', '',  $total_soles, $total_dolar]);
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#cccccc');
    });
  }

  public function processItems()
  {
    $pagos_group = $this->data['pagos_group'];

    $general_total_soles = 0;
    $general_total_dolares = 0;

    foreach ($pagos_group as $pagoTipo =>  $items) {

      $pagoTipoNombre = $this->tipoPagoHeader($pagoTipo);
      $this->getHeaderTable();

      foreach ($items as $item) {
        
        $this->sheet->row($this->getLineaAndSum(), [
          $item->VtaOper,
          $item->PagFech,
          $item->PagBoch,
          $item->cliente->PCNomb,
          $item->VtaNume,
          $item->getMonedaNombre(),
          $item->PagTCam,
          $item->PagImpo,
        ]);
      }

      $general_total_soles +=  $total_soles = decimal($items->where('MonCodi','01')->sum('PagImpo'));
      $general_total_dolares +=  $total_dolares = decimal($items->where('MonCodi','02')->sum('PagImpo'));
      $this->tipoPagoTotal($pagoTipoNombre, $total_soles, $total_dolares);
    }

    $this->totalGeneral($general_total_soles , $general_total_dolares );
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
      $this->sheet = $sheet;
      $this->sheet->setAutoSize(false);
      $this->sheet->setFontFamily('Arial');
      $this->sheet->setFontSize(10);
      $this->headerSheet();
      $this->processItems();
    });
  }
}
