<?php
namespace App\Util\Import\Excell\Cliente;

use App\Util\ExcellGenerator\LineTrait;
use Illuminate\Support\Facades\Validator;
use App\Util\Import\Excell\Producto\ResultTrait;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Collections\SheetCollection;

class ValidateCliente
{
  use
    LineTrait,
    ResultTrait;

  protected $sheet;
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
    "tipo_cliente",
    "tipo_documento"	,
    "documento"	,
    "nombre"	,
    "direccion"	,
    "telefono"	,
    "correo"	,
    "ubigeo"	,
    "zona"	,
    "cvend"	,
    "vendedor"
  ];

  public function __construct($sheet, $items_qty_limit = false)
  {
    $this->sheet = $sheet;
    $this->items_qty_limit = $items_qty_limit;
    $this->rulesItems = new RulesClients();

    $this->setStarLine(2);
  }
  
  public function validateHeader()
  {
    $headers_names_error = array_diff($this->sheet->getHeading(), self::HEADERS);
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
    // if ($this->items_qty_limit === false) {
    //   return true;
    // }

    // if ($this->items_qty > $this->items_qty_limit) {

    //   return $this->addError(
    //     sprintf(
    //       'La cantidad de productos (%s), excede la cantidad permitida a su cuenta(%s)',
    //       $this->items_qty,
    //       $this->items_qty_limit
    //     )
    //   );
    // }

    // return true;
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
 
    
    if( isset($item['tipo_cliente']) == false ){
      return null;
    }

    if ( $item['tipo_cliente'] === null || trim($item['tipo_cliente']) == "") {
      return null;
    }
    
    $success = true;

    // $this->rulesItems->setData( $item['tipo_cliente'], $item['tipo_documento'] );
    $this->rulesItems->setData( $item['tipo_cliente'], $item['tipo_documento'] );

    $validator = Validator::make($item, $this->rulesItems->getRules(), []);

    $this->rulesItems->addDocumento( $item['tipo_cliente'] . '-' . $item['documento'] );

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
    $items_chunk = $this->sheet->chunk(50);
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
    if (!$this->validateHeader()) {
      return $this;
    }

    $this->validateRows();

    $this->validateRowsCantidad();
    return $this;
  }
}
