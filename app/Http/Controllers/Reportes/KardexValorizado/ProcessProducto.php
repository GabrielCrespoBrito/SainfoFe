<?php

namespace App\Http\Controllers\Reportes\KardexValorizado;

use Exception;

class ProcessProducto
{
  # 
  const QUANTITY = 'quantity';
  const COST_UNIT = 'cost_unit';
  const TOTAL = 'total';
  #
  const ENTRADA = 'entrada';
  const SALIDA = 'salida';
  const SALDO = 'saldo';
  #
  const FORM_DATA = [
    self::QUANTITY => 0,
    self::COST_UNIT => 0,
    self::TOTAL => 0,
  ];

  # Información que va a utilizar el reporte
  protected $dataReporte = [];

  # Registros de la busqueda en bd
  protected $items;

  # Si se va a registrar información que se va generando de los items en el array de rowData
  protected $withRows;

  #Item actual
  protected $currentItem;

  # Donde vamos a ir guardando la data
  protected $rowData = [];

  # Total
  protected $sald = [
    'quantity' => 0,
    'cost_unit' => 0,
    'total' => 0,

    // Lo que vamos llevando de ingreso y salida
    'cant_total_ingreso' => 0,
    'cant_total_salida' => 0,
  ];

  /**
   * Items
   */
  public function  __construct($items, $withRows = true)
  {
    $this->items = $items;
    $this->withRows = $withRows;
  }

  public function setSaldo($values)
  {
    $this->sald['quantity'] = $values['quantity'];
    $this->sald['cost_unit'] = $values['cost_unit'];
    $this->sald['total'] = $values['total'];
  }

  /**
   * Agregar nuevos valores de cantidad y total, y calcular el costo unitario
   * 
   * @return void
   */
  public function addToSaldAndCalculate($values, $isAgregate = true)
  {
    $quantity = $isAgregate ? $values->quantity : -$values->quantity;

    // Cantidad
    if ($isAgregate) {
      $this->sald['quantity'] += $values->quantity;
      $this->sald['cant_total_ingreso'] += $values->quantity;
    } else {
      $this->sald['quantity'] += -$values->quantity;
      $this->sald['cant_total_salida'] += $values->quantity;
    }


    // Si es un ingreso, hacer el calculo, si no dejar el costo como esta
    if ($isAgregate) {
      $this->sald['cost_unit'] = $this->sald['quantity'] == 0 ? 0 : ($this->sald['total'] + $values->total) / $this->sald['quantity'];
    }

    // Total
    $this->sald['total'] = $this->sald['cost_unit'] * $this->sald['quantity'];
  }

  /**
   * Item actual 
   * 
   * @return void
   */
  public function setCurrentItem($item)
  {
    $this->currentItem = $item;
  }

  /**
   * Item actual 
   * 
   * @return object
   */
  public function getItem()
  {
    return $this->currentItem;
  }

  /**
   * Determinar si es un ingreso o salida
   * 
   * @return bool
   */
  public function isIngreso()
  {
    return $this->getItem()->EntSal === "I";
  }

  /**
   * Obtener cantidad real de un item
   * 
   * @return float
   */
  public function getRealQuantity()
  {
    return get_real_quantity(
      $this->getItem()->UniEnte,
      $this->getItem()->UniMedi,
      $this->getItem()->Detcant
    );
  }


  /**
   * Agregar a columna los datos a columna
   * 
   * @return void
   */
  public function addToRow(object $values)
  {
    $docRef = explode('-', $this->getItem()->docrefe);
    $serie = $docRef[1] ?? '';
    $numero = $docRef[2] ?? '';

    # Las 3 Columnas  de ingresos, salidas y saldo y sus tres correspondientes valores
    $currentData = [
      'entrada' => self::FORM_DATA,
      'salida' => self::FORM_DATA,
      'saldo' => self::FORM_DATA,
      'info' => [
        'fecha' => $this->getItem()->GuiFemi,
        'tipo_documento' => $this->getItem()->TidCodi,
        'serie' => $serie,
        'numero' => $numero,
        'tipo_operacion' => "01",
      ],
    ];

    $columnaToAdd = $this->isIngreso() ? 'entrada' : 'salida';

    // Agregar a la columna correspondiente
    $currentData[$columnaToAdd] = [
      'quantity' => $values->quantity,
      'cost_unit' => $values->cost_unit,
      'total' => $values->total,
    ];


    // Agregar a la columna de saldo
    $currentData['saldo'] = [
      'quantity' => $this->sald['quantity'],
      'cost_unit' => $this->sald['cost_unit'],
      'total' => $this->sald['total'],
    ];

    // dump("currentData", $currentData );
    array_push($this->rowData, $currentData);
  }


  /**
   * Obtener los valores calculados del item actual
   * 
   * @return object
   */
  public function obtainValues()
  {

    $values = [];

    # Cantidad
    $values['quantity'] = $this->getItem()->Detcant;
    # Cost Unit
    $values['cost_unit'] = $this->isIngreso() ? $this->getItem()->DetPrec : $this->sald['cost_unit'];
    # Total
    $values['total'] = $values['quantity'] * $values['cost_unit'];

    return (object) $values;
  }

  /**
   * Procesar los items
   * 
   * @return void
   */
  public function processItems()
  {
    foreach ($this->items as $item) {
      $this->setCurrentItem($item);
      $values = $this->obtainValues();
      $this->addToSaldAndCalculate($values, $this->isIngreso());
      if ($this->withRows) {
        $this->addToRow($values);
      }
    }
  }

  /**
   * Obtener el saldo
   * 
   * @return array
   */
  public function getSald()
  {
    return $this->sald;
  }

  /**
   * Obtener las filas 
   * 
   * @return array
   */
  public function getData()
  {
    return $this->rowData;
  }
}
