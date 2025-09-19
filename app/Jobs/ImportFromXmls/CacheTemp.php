<?php


namespace App\Jobs\ImportFromXmls;

use App\Caja;
use App\Local;
use App\Venta;
use App\Producto;
use App\Vendedor;
use App\VentaItem;
use App\ClienteProveedor;
use App\TipoCambioPrincipal;

class CacheTemp
{
  public $vendedorDefault = null;
  public $userPrincipal = null;
  public $userPrincipalUserName = null;
  public $localDefault = null;
  public $clienteList = [];
  public $tipoCambioList = [];
  public $cajasList = [];
  public $ventaOper = null;
  public $linea;
  public $productoList = [];

  public function __construct() {}

  public function getUserPrincipal()
  {
    if ($this->userPrincipal) {
      return $this->userPrincipal;
    }

    return $this->userPrincipal = get_empresa()->userOwner()->id();
  }

  public function getUserPrincipalUserName()
  {
    if ($this->userPrincipalUserName) {
      return $this->userPrincipalUserName;
    }

    $userOwner = get_empresa()->userOwner();

    $this->userPrincipal = $userOwner->id();
    return  $this->userPrincipalUserName = $userOwner->usulogi;
  }


  public function getCajNume($fecha)
  {
    $key = $this->userPrincipal . '-' . $fecha;
    if (isset($this->cajasList[$key])) {
      return $this->cajasList[$key];
    }

    $caja = Caja::where('UsuCodi', $this->userPrincipal)
      ->where('CajFech', $fecha)
      ->where('CueCodi', '0000')
      ->first();

    return $this->cajasList[$key] = optional($caja)->CajNume;
  }



  public function getVentaOper()
  {
    if ($this->ventaOper) {
      return $this->ventaOper = math()->addCero($this->ventaOper + 1, 6);
    }


    return $this->ventaOper = Venta::UltimoId();
  }

  public function getLinea()
  {
    if ($this->linea) {
      return $this->linea = math()->addCero($this->linea + 1, 8);
    }

    return $this->linea = VentaItem::nextLinea();
  }


  public function getProducto($descripcion)
  {
    if (isset($this->productoList[$descripcion])) {
      return $this->productoList[$descripcion];
    }

    $producto = Producto::where('ProNomb', $descripcion)->first();
    $producto = $producto ?? Producto::first();

    $productoData = [
      'ProCodi' => $producto->ProCodi,
      'ProNomb' => $descripcion,
      'UniAbre' => $producto->unpcodi,
      'UniCodi' => $producto->unidadPrincipal()->UniCodi,
    ];

    return $this->productoList[$descripcion] = $productoData;
  }

  /*
      $productoData = $this->cacheTemp->getProducto($item['item_descripcion']);

      $dataItem['Linea'] = $this->cacheTemp->getLinea();
      $dataItem['DetItem'] = $item['item_orden'] + 1;
      $dataItem['VtaOper'] = $this->venta->VtaOper;
      $dataItem['EmpCodi'] = $this->empresaId;
      $dataItem['DetUnid'] = $productoData['UniAbre'];
      $dataItem['DetCodi'] = $productoData['ProCodi'];
      $dataItem['DetNomb'] = $productoData['ProNomb'];
  */


  public function getVendedorDefault()
  {
    if ($this->vendedorDefault) {
      return $this->vendedorDefault;
    }
    $this->vendedorDefault = Vendedor::first();
    return $this->vendedorDefault;
  }

  public function getLocalDefault()
  {
    if ($this->localDefault) {
      return $this->localDefault;
    }
    $this->localDefault = Local::first()->LocCodi;
    return $this->localDefault;
  }

  public function getCliente($documento,  $nombre, $tipodocumento, $empresaId)
  {

    $key = $documento . '-' . $tipodocumento;

    if (isset($this->clienteList[$key])) {
      return $this->clienteList[$key];
    }


    $clienteCodigo = ClienteProveedor::findOrCreateByRuc(
      $documento,
      $nombre,
      $tipodocumento,
      $empresaId,
      $this->userPrincipal
    )->PCCodi;

    $this->clienteList[$key] = $clienteCodigo;
    return $clienteCodigo;
  }

  public function getTipoCambio($fecha)
  {
    if (isset($this->tipoCambioList[$fecha])) {
      return $this->tipoCambioList[$fecha];
    }

    $tc = TipoCambioPrincipal::where('TipFech', $fecha)->first();

    return $this->tipoCambioList[$fecha] = $tc ? $tc->TipVent : 3.546;
  }
}
