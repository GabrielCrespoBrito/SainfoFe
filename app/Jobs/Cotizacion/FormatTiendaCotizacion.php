<?php

namespace App\Jobs\Cotizacion;

use App\Producto;
use App\TipoDocumento;
use App\ClienteProveedor;
use App\Rules\DniValidation;
use App\Rules\RucValidation;
use App\Woocomerce\WoocomerceOrder;
use App\Util\ConsultDocument\ConsultDniMigo;
use App\Util\ConsultDocument\ConsultRucMigo;

class FormatTiendaCotizacion
{
  public $id;
  public $result;
  public $woocomerceData;
  public $data;

  public function __construct($id, &$data)
  {
    $this->setId($id);
    $data['import'] = true;
    $data['importInfo'] = [
      'success' => false,
      'id' => $id,
      'messages' => [],
    ];
    
    $this->setData($data);
  }

  public function processClientePart()
  {
    //20876588
    $documento =  $this->woocomerceData['cliente']['documento'];
    $razonSocial = $this->woocomerceData['cliente']['razon_social'];
    $email = $this->woocomerceData['cliente']['email'];
    $telefono = $this->woocomerceData['cliente']['telefono'];

    $data = [
      'tipo_documento' => null,
      'nombre_cliente' => $razonSocial,
      'documento' => $documento,
      'direccion' => '',
      'telefono' => $telefono,
      'email' => $email,
      'ubigeo' => null,
    ];


    $cliente = ClienteProveedor::findByRuc($documento);

    if( $cliente == null ){

      // Dni
      if( strlen($documento) == 8 ){

        $isValid = (new DniValidation(false))->passes(null,$documento);
        if( $isValid ){
          $consult =  (new ConsultDniMigo)->consult($documento);
          if( $consult['success'] ){
            $data['tipo_documento'] = TipoDocumento::DNI;
            $data['nombre_cliente'] = $consult['data']['razon_social'];
            $cliente = ClienteProveedor::createCliente($data);
          }
          else {
            $this->data['importInfo']['messages'][] = sprintf('No se pudo guardar el cliente ya que el nº de documento %s no corresponde a un DNI/RUC valido', $documento);
          }
        }

        else {
          $this->data['importInfo']['messages'][] = sprintf('No se pudo guardar el cliente ya que el nº de documento %s no corresponde a un DNI/RUC valido', $documento);
        }
      }

      // Ruc
      else if(strlen($documento) == 11) {
        
        $isValid = (new RucValidation(false))->passes(null, $documento);
        //
        if ($isValid) {
          $consult =  (new ConsultRucMigo)->consult($documento);
          if ($consult['success']) {
            $data['tipo_documento'] = TipoDocumento::RUC;
            $data['nombre_cliente'] = $consult['data']['razon_social'];
            $data['direccion'] = $consult['data']['direccion'];
            $data['ubigeo'] = $consult['data']['ubigeo'];
            $cliente = ClienteProveedor::createCliente($data);
          } else {
            $this->data['importInfo']['messages'][] = sprintf('No se pudo guardar el cliente ya que el nº de documento %s no corresponde a un DNI/RUC valido', $documento);
            // $data['documento'] = ".";
            // $data['tipo_documento'] = TipoDocumento::NINGUNA;
          }
        } 
        
        else {
          $this->data['importInfo']['messages'][] = sprintf('No se pudo guardar el cliente ya que el nº de documento %s no corresponde a un DNI/RUC valido', $documento);
        }
      } 
      
      else {
        $this->data['importInfo']['messages'][] = sprintf('No se pudo guardar el cliente ya que el nº de documento %s no corresponde a un DNI/RUC valido', $documento);
      }

    }

    // Agregando la información del cliente

    if($cliente){
      $this->data['cliente_id'] = $cliente->PCCodi;
      $this->data['cliente_descripcion'] = $cliente->PCNomb;
      $this->data['cliente_tipo'] =  TipoDocumento::getNombre($cliente->TdoCodi);
    }
  }

  public function processProducts()
  {
    $items_woocomerce = $this->woocomerceData['items'];
    $items = [];

    foreach($items_woocomerce as $item_woocomerce ){
      
      $producto = Producto::findByProCodi($item_woocomerce->sku);

      if($producto){
        $productoData = [
          'producto' => $producto,
          'cantidad' => $item_woocomerce->quantity,
        ];
        $items[] = $productoData;
      }

      else {
        $this->data['importInfo']['messages'][] = sprintf('No se pudo agregar un item (%s -  %s) a la cotización', $item_woocomerce->sku, $item_woocomerce->name);
      }
    }

    $this->data['importInfo']['items'] = $items;
  }

  public function processData()
  {
    $this->data['importInfo']['woocomerce_data'] = $this->getWoocomerceData();

    $this->processClientePart();
    $this->processProducts();
  }

  public function process()
  {
    $woocomerce = (new WoocomerceOrder())->get($this->getId());
      
      if($woocomerce->success){
        $this->data['importInfo']['success'] = true;
        $this->setWoocomerceData($woocomerce->data);
        $this->processData();
    }

    else {
      $this->data['importInfo']['messages'][] = 'No se pudo realizar la importación de la cotización de la tienda, por favor recargue la pagina';
    }

    // $this->setResult((object) $result);

    return $this;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    return $this
    ->process()
    ->getData();
  }

  /**
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of result
   */ 
  public function getResult()
  {
    return $this->getData();
  }

  /**
   * Set the value of result
   *
   * @return  self
   */ 
  public function setResult($result)
  {
    $this->result = $result;

    return $this;
  }

  /**
   * Get the value of woocomerceData
   */ 
  public function getWoocomerceData()
  {
    return $this->woocomerceData;
  }

  /**
   * Set the value of woocomerceData
   *
   * @return  self
   */ 
  public function setWoocomerceData($woocomerceData)
  {
    $this->woocomerceData = $woocomerceData;

    return $this;
  }

  /**
   * Get the value of data
   */ 
  public function getData()
  {
    return $this->data;
  }

  /**
   * Set the value of data
   *
   * @return  self
   */ 
  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }
}
