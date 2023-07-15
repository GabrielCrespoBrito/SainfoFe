<?php

namespace App\Util\ExcellGenerator;


class CompraReporteExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER = [
    'Fecha',
    'N° Oper.',
    'N° Doc.',
    'RUC/DNI',
    'Razón social',
    'Mon',
    'Valor',
    'IGV',
    'Total',
    'Perc',
    'Total S/',
    'T.C',
  ];

  public $title_hoja = "";
  public $linea = 1;

  const TITLE_HOJA = "REPORTE DE COMPRAS";

  protected $infoProductsInfo = [];

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

  public function processProductos()
  {
    $items_group = $this->data['items_group'];

    // dd( $items );
    // exit();
    $subTotal = 0;
    $igv = 0;
    $total = 0;

    $nombresDocuentos = ['01' => 'FACTURA', '03' => 'BOLETA', '07' => 'NOTA CREDITO', '08' => 'NOTA DEBITO', '40' => 'COMPRA LIBRE'];

    foreach ($items_group as $key => $items) {

      $subTotalGroup = 0.00;
      $igvGroup = 0.00;
      $totalGroup = 0.00;

      // Nombre de producto
      $this->sheet->row($lineHeader =  $this->getLineaAndSum(), [
        $key,
        $nombresDocuentos[$key],
        ]  
      );

      // Poner Negrita
      $this->sheet->row($lineHeader, function ($row) {
        $row->setFontWeight('bold');
      });


      foreach( $items as $item ){

        $tCambio = $item->MonCodi == "01" ? '-' : $item->CpaTcam;
        $subTotalGroup += $item->MonCodi == "01" ? $item->Cpabase : $item->Cpabase * $item->CpaTcam;
        $igvGroup += $item->MonCodi == "01" ? $item->CpaIGVV : $item->CpaIGVV * $item->CpaTcam;
        $totalGroup += $totalSoles = $item->MonCodi == "01" ? $item->Cpatota : $item->Cpatota * $item->CpaTcam;

        $this->sheet->row( $this->getLineaAndSum(), [
            $item->CpaFCpa,
            $item->CpaOper,
            $item->CpaNume,
            $item->PCRucc,
            $item->PCNomb,
            $item->monnomb,
            math()->addDecimal($item->Cpabase),
            math()->addDecimal($item->CpaIGVV),
            math()->addDecimal($item->Cpatota),
             "0.00",
            $totalSoles,
            $tCambio
        ]);

      }

      $subTotal += $subTotalGroup;
      $igv += $igvGroup;
      $total += $totalGroup;


      $this->sheet->row( $lineTotal = $this->getLineaAndSum(), [
        "SubTotal Valor Cpa S./",
        $subTotalGroup,
        "SubTotal IGV S./",
        $igvGroup,
        "SubTotal S./",
        $totalGroup,        
      ]);

      // Poner Negrita
      $this->sheet->row($lineTotal, function ($row) {
        $row->setFontWeight('bold');
        $row->setBackground('#CCCCCC');
      });
    }

    $this->sheet->row($lineTotal = $this->getLineaAndSum(), [
      "Total Valor Cpa S./",
      $subTotal,
      "Total IGV S./",
      $igv,
      "Total S./",
      $total
    ]);

    // Poner Negrita
    $this->sheet->row($lineTotal, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#CCCCCC');
    });

  }


  public function setHeaders()
  {
    /*
     'nombre_reporte' => 'Reporte de compras',
      'nombre_empresa' => $empresa->EmpNomb,
      'ruc' => $empresa->EmpLin1,
      'fecha_desde' => $request->fecha_desde,
      'fecha_hasta' => $request->fecha_hasta,
      'proveedor' => $proveedor,
      'items_group' => $busqueda,
      'withProducts' => $request->input('products',false),
    */

    //  'nombre_reporte' => 'Reporte de compras',
    //   'nombre_empresa' => $empresa->EmpNomb,
    //   'ruc' => $empresa->EmpLin1,
    //   'fecha_desde' => $request->fecha_desde,
    //   'fecha_hasta' => $request->fecha_hasta,
    //   'proveedor' => $proveedor,
    //   'items_group' => $busqueda,
    //   'withProducts' => $request->input('products',false),

    // 
    $this->sheet->row($line =  $this->getLineaAndSum(), [
      $this->data['nombre_empresa']  . ' ' .  $this->data['ruc'],
      // 
    ]);

    $this->sheet->row( $lineNombreReporte = $this->getLineaAndSum(), [ $this->data['nombre_reporte']]);

    $this->sheet->row($this->getLineaAndSum(), ['FECHA DESDE',  $this->data['fecha_desde']]);

    $this->sheet->row($this->getLineaAndSum(), ['FECHA HASTA',  $this->data['fecha_hasta']]);

    $this->sheet->row($this->getLineaAndSum(), ['PROVEEDOR',  $this->data['proveedor']]);

    $this->sheet->row($this->getLineaAndSum(), ['TIPO DOCUMENTO',  $this->data['tipodocumento']]);


    // Poner Negrita
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($lineNombreReporte, function ($row) {
      $row->setFontWeight('bold');
    });

  }


  public function setHeaderTable()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getHeader() );

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });
  }


  public function setHeaderProduct()
  {
    $this->sumLinea();

    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getHeader());

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
      $sheet->setFontFamily('Arial');
      $sheet->setFontSize(10);
      $this->sheet = $sheet;
      $this->setHeaders();
      $this->setHeaderTable();
      $this->processProductos();
    });
  }
}
