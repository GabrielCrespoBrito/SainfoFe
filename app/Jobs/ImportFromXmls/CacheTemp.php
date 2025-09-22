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
    return  '01';
  }

  public function getUserPrincipalUserName()
  {
    return 'FONSECA';
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
    // Si no hay línea inicial, obtener la última línea de la base de datos
    if (!$this->linea) {
      $this->linea = VentaItem::orderByDesc('Linea')->value('Linea');
      // Si no hay registros, empezar desde 0
      if (!$this->linea) {
        $this->linea = 0;
      }
    }
    
    // Incrementar y formatear con ceros a la izquierda
    $this->linea = $this->linea + 1;
    return math()->addCero($this->linea, 8);
  }


  public function getProducto($descripcion)
  {
    if (isset($this->productoList[$descripcion])) {
      return $this->productoList[$descripcion];
    }

    $producto = Producto::where('ProNomb', $descripcion)->first();
    $producto = $producto ?? Producto::first();
    $unidad = $producto->unidadPrincipal();

    $productoData = [
      'ProCodi' => $producto->ProCodi,
      'ProNomb' => $descripcion,
      'unidad' => $unidad,
      'UniAbre' => $producto->unpcodi,
      'UniCodi' => $unidad->Unicodi,
      'incluye_igv' => false,
    ];

    return $this->productoList[$descripcion] = $productoData;
  }
  
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
