<?php

namespace App\Util\Import\Excell\Producto;

use App\Util\ExcellGenerator\LineTrait;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Collections\RowCollection;

class ValidateProducto
{
  use
    LineTrait,
    ResultTrait;

  protected $productIds = [];
  protected $productsCodigoBarra = [];
  protected $rulesItems;

  public $linea;
  protected $excell;

  /**
   * Parametro para saber si se va a evaluar la cantidad de items
   * 
   */
  protected $items_qty_limit = false;
  /**
   * Cantidad de productos reales
   * 
   */
  protected $items_qty = null;

  const HEADERS = [
    'codigo_unico',
    'codigo_barra',
    'categoria',
    'marca',
    'unidad',
    'nombre',
    'moneda',
    'costo_dolares',
    'costo_soles',
    'margen',
    'precio_soles',
    'precio_dolares',
    'peso',
    'base_igv',
    'incluye_igv',
    'tipo_existencia',
    'unidades',
  ];

  public function __construct($excell, $items_qty_limit = false)
  {
    $this->excell = $excell;
    $this->items_qty_limit = $items_qty_limit;
    $this->rulesItems = new RulesItems();

    $this->setStarLine(2);
  }

  public function validateSheet()
  {
    if ($this->excell instanceof RowCollection == false) {
      return $this->addError('El archivo solo tiene que tener una hoja');
    }

    return true;
  }

  public function validateHeader()
  {
    $headers_names_error = array_diff($this->excell->getHeading(), self::HEADERS);
    $count = count($headers_names_error);

    if ($count === 0) {
      return true;
    }

    $error = false;

    foreach ($headers_names_error as $name_error) {
      if (trim($name_error) != "") {
        $error = true;
      }
    }

    if ($error == false) {
      return true;
    }

    return $this->addError(
      sprintf(
        'Error en la cabecera: Su excell tiene nombres de campos que no validos (%s)',
        implode(',', $headers_names_error)
      )
    );
  }



  public function validateRowsCantidad()
  {
    if ($this->items_qty_limit === false) {
      return true;
    }

    if ($this->items_qty > $this->items_qty_limit) {

      return $this->addError(
        sprintf(
          'La cantidad de productos (%s), excede la cantidad permitida a su cuenta(%s)',
          $this->items_qty,
          $this->items_qty_limit
        )
      );
    }

    return true;
  }

  public function getRulesItem()
  {
    return $this->rules_items;
  }

  public function messsageErrorLine($message)
  {
    return sprintf("Error Linea %s | %s", $this->getLinea(), $message);
  }

  public function validateRow($item)
  {
    $item = $item->toArray();

    if ($item['codigo_unico'] === null || trim($item['codigo_unico']) == "") {
      return null;
    }
    
    $success = true;
    $validator = Validator::make($item, $this->rulesItems->getRules(), [
      'codigo_unico.not_in' =>  "El Codigo Unico ({$item['codigo_unico']}) del Producto esta repetido",
      'codigo_barra.not_in' =>  "El Codigo de Barra ({$item['codigo_barra']}) del Producto esta repetido"
    ]);

    $this->rulesItems->setCodigos(
      $item['codigo_unico'],
      $item['codigo_barra']
    );

    if ($validator->fails()) {
      $success = false;
      $errors = array_flat($validator->errors()->toArray());
      $errors_format = [];
      foreach ($errors as $error) {
        array_push($errors_format, $this->messsageErrorLine($error));
      }
      $this->addError($errors_format);
    }
    return $success;
  }

  public function validateRows()
  {
    $success = true;
    // $items = $this->excell->chunk(50);
    $items_chunk = $this->excell->chunk(50);
    $count_items = count($items_chunk);

    if ($count_items == 0) {
      return $this->addError('El Excell no puede estar vacio, tiene que haber al menos un (1) registro');
    }

    $items_qty = 1;

    foreach ($items_chunk as $items) {
      # code...
      foreach ($items as $item) {

        $result = $this->validateRow($item);
        $this->sumLinea();

        if ($result === null) {
          break;
        }

        $items_qty++;

        if (!$result) {
          $success = false;
        }
      }

      $this->items_qty = $items_qty;

      return $success;
    }
  }

  public function handle()
  {
    if (!$this->validateSheet()) {
      return $this;
    }

    if (!$this->validateHeader()) {
      return $this;
    }

    $this->validateRows();
    $this->validateRowsCantidad();
    return $this;
  }
}
