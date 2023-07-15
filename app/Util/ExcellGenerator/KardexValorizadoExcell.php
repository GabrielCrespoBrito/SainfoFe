<?php

namespace App\Util\ExcellGenerator;


class KardexValorizadoExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER = [
    "DOCUMENTOS", "", "", "", "",
    "ENTRADAS", "", "",
    "SALIDAS", "", "",
    "SALDO TOTAL"
  ];


  const SUBHEADER = [
    // DOCUMENTOS
    "FECHA",
    "TD",
    "SERIE",
    "NUMERO",
    "T.OPER",
    // ENTRADAS
    "CANTIDAD",
    "COSTO UN",
    "COSTO TOT",
    // SALIDAS
    "CANTIDAD",
    "COSTO UN",
    "COSTO TOTAL",
    // SALDO TOTAL
    "CANTIDAD",
    "COSTO UN",
    "COSTO",
  ];

  public $title_hoja = "";
  public $linea = 1;

  public $periodo = "";
  public $establecimientos = "";
  public $nombreEmpresa;
  public $rucEmpresa;


  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";

  # Información de los items
  const FORMATO_NOMBRE = 'FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO"';
  const METODO_EVALUACION = 'MÉTODO DE VALUACIÓN: PROMEDIO';

  // setProductoMainData  
  // PERÍODO: septiembre 2018 
  // RUC: 20520758829 
  // APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL: INVERSIONES PRADA S.A.C. 
  // ESTABLECIMIENTO (1): 
  // CÓDIGO DE LA EXISTENCIA: 00001 
  // TIPO: 01 - MERCADERÍA 
  // DESCRIPCIÓN: CEMENTO SOL 
  // CÓDIGO DE LA UNIDAD DE MEDIDA: 07 - UNIDADES 
  // MÉTODO DE VALUACIÓN: PROMEDIO

  protected $infoProductsInfo = [];


  public function __construct($data, $title_hoja = "hoja_excell")
  {
    parent::__construct($data, true, null);

    $this->title_hoja = $title_hoja;


    $this->setProductoMainData();
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSubHeader()
  {
    return self::SUBHEADER;
  }

  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function setProductTableHeader()
  {
    $this->sheet->row($this->getLinea(), $this->getHeader());
    $this->sheet->mergeCells($this->getTextWihLinea('A%l:E%l'));
    $this->sheet->mergeCells($this->getTextWihLinea('F%l:H%l'));
    $this->sheet->mergeCells($this->getTextWihLinea('I%l:K%l'));
    $this->sheet->mergeCells($this->getTextWihLinea('L%l:N%l'));

    $this->sheet->row($this->getLineaAndSum(), function ($row) {
      // $row->setFontWeight('bold');
      $row->setAlignment('center');
    });

    $this->sheet->row($this->getLinea(), $this->getSubHeader());

    $this->sheet->row($this->getLineaAndSum(), function ($row) {
      // $row->setFontWeight('bold');
    });
  }


  public function setProductDetails($producto)
  {
    $movimientos = $producto['items'];

    $total_ingreso_cantidad = 0;
    $total_ingreso_total = 0;
    $total_egreso_cantidad = 0;
    $total_egreso_total = 0;

    foreach ($movimientos as $mov) {

      $this->sheet->row($this->getLineaAndSum(), [
        // **************************************************************************************************************
        $mov['info']['fecha'],
        $mov['info']['tipo_documento'],
        $mov['info']['serie'],
        $mov['info']['numero'],
        $mov['info']['tipo_operacion'],
        // Entrada ******************************************************************************************************
        $mov['entrada']['quantity'],
        $mov['entrada']['cost_unit'],
        $mov['entrada']['total'],
        // Salida ******************************************************************************************************
        $mov['salida']['quantity'],
        $mov['salida']['cost_unit'],
        $mov['salida']['total'],
        // Saldo  ******************************************************************************************************
        $mov['saldo']['quantity'],
        $mov['saldo']['cost_unit'],
        $mov['saldo']['total'],
      ]);

      $total_ingreso_cantidad = $total_ingreso_cantidad + $mov['entrada']['quantity'];
      $total_ingreso_total = $total_ingreso_total + $mov['entrada']['total'];
      $total_egreso_cantidad = $total_egreso_cantidad + $mov['salida']['quantity'];
      $total_egreso_total = $total_egreso_total + $mov['salida']['total'];
    }

    return [
      'ingreso_cantidad' => $total_ingreso_cantidad,
      'ingreso_total' => $total_ingreso_total,
      'egreso_cantidad' => $total_egreso_cantidad,
      'egreso_total' => $total_egreso_total,
    ];
  }

  public function setProductoMainData()
  {
    $this->infoProductsInfo = [
      'formato_nombre' => self::FORMATO_NOMBRE,
      'metodo' => self::METODO_EVALUACION,
      'razon_social' => "APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL: " .  $this->data['nombre'],
      'ruc_empresa' => "RUC: " .  $this->data['ruc'],
      'establecimientos' =>  "ESTABLECIMIENTOS ({$this->data['codigo_local']}) ",
      'periodo' =>  "PERÍODO: {$this->data['periodo']}",
    ];
  }



  public function getFormatInfoProduct($producto)
  {
    $info_producto = [
      'codigo_existencia' => "CÓDIGO DE LA EXISTENCIA: {$producto['code']}",
      'tipo' => sprintf('TIPO: %s - %s', $producto['codigo_tipo_existencia'], $producto['nombre_tipo_existencia']),
      'descripcion' => 'DESCRIPCIÓN: ' . $producto['descripcion'],
      'codigo_unidad' => sprintf('CÓDIGO DE LA UNIDAD DE MEDIDA: %s - %s', $producto['codigo_unidad'], $producto['nombre_unidad']),
    ];

    return (object) array_merge($this->infoProductsInfo, $info_producto);
  }

  public function setProductoDescripcion($producto)
  {
    $info = $this->getFormatInfoProduct($producto);

    $this->sheet->row($this->getLineaAndSum(), [$info->formato_nombre]);
    $this->sheet->row($this->getLineaAndSum(), [$info->periodo]);
    $this->sheet->row($this->getLineaAndSum(), [$info->ruc_empresa]);
    $this->sheet->row($this->getLineaAndSum(), [$info->razon_social]);
    $this->sheet->row($this->getLineaAndSum(), [$info->establecimientos]);
    $this->sheet->row($this->getLineaAndSum(), [$info->codigo_existencia]);
    $this->sheet->row($this->getLineaAndSum(), [$info->tipo]);
    $this->sheet->row($this->getLineaAndSum(), [$info->descripcion]);
    $this->sheet->row($this->getLineaAndSum(), [$info->codigo_unidad]);
    $this->sheet->row($this->getLineaAndSum(), [$info->metodo]);
  }

  public function processProductos()
  {
    $items = $this->data['data'];

    foreach ($items as $producto) {
      $this->setProductoDescripcion($producto);
      $this->setProductTableHeader();
      $totals = $this->setProductDetails($producto);

      // Poner totales
      $this->sheet->row($line =  $this->getLineaAndSum(2), [
        "", "", "", "", "",
        $totals['ingreso_cantidad'],
        "",
        $totals['ingreso_total'],
        $totals['egreso_cantidad'],
        "",
        $totals['egreso_total']
      ]);

      // Poner Negrita
      $this->sheet->row($line, function ($row) {
        $row->setFontWeight('bold');
      });
    }
  }

  public function procces(&$excel)
  {
    $this->setLinea(2);

    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $sheet->setAutoSize(false);
      $sheet->setFontFamily('Arial');
      $sheet->setFontSize(10);

      $this->sheet = $sheet;
      $this->processProductos();
    });
  }
}
