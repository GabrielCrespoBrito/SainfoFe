<?php

namespace App\Util\Import\Excell\Producto;

use App\Util\ExcellGenerator\LineTrait;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Collections\RowCollection;

class ValidateTomaInventarioExcellImport
{
  use
    LineTrait,
    ResultTrait;

  protected $productIds = [];
  public $rulesItems;

  public $linea;
  protected $excell;

  const HEADERS = [
    'codigo',
    'nombre',
    'stock',
  ];


  public function __construct($excell)
  {
    $this->excell = $excell;
    $this->rulesItems = new RuleItemsToma();
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

    if ($item['codigo'] === null) {
      return null;
    }

    $success = true;

    $validator = Validator::make($item, $this->rulesItems->getRules(), [
      'codigo.in' =>  "El Codigo ({$item['codigo']}) No Existe",
      'codigo.not_in' =>  "El Codigo ({$item['codigo']}) del Producto esta repetido",
    ]);

    $this->rulesItems->setCodigo($item['codigo']);


    // Error
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

    foreach ($items_chunk as $items) {
      foreach ($items as $item) {

        $result = $this->validateRow($item);
        $this->sumLinea();
        if ($result === null) {
          break;
        }
        if (!$result) {
          $success = false;
        }
      }
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
    return $this;
  }
}
