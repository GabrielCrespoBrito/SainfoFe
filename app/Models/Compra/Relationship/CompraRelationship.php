<?php 

namespace App\Models\Compra\Relationship;

use App\Caja;
use App\Moneda;
use App\TipoPago;
use App\FormaPago;
use App\CompraItem;
use App\GuiaSalida;
use App\CajaDetalle;
use App\ClienteProveedor;
use App\Models\Compra\CompraPago;

trait CompraRelationship
{
  /**
   * Guia de entrada
   * 
   */
  public function guia()
  {
    return $this->belongsTo( GuiaSalida::class, 'cpaOper', 'CpaOper')->where('EmpCodi' , $this->EmpCodi );
  }
  
  /**
   * Guia de entrada
   * 
   */
  public function guias()
  {
    return $this->hasMany( GuiaSalida::class, 'cpaOper', 'CpaOper');
  }

  public function items()
  {
    return $this->hasMany(CompraItem::class, 'CpaOper', 'CpaOper');
  }
      
  public function cliente()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCcodi', 'PCCodi')
      ->where('EmpCodi', $this->EmpCodi)
      ->where('TipCodi', 'P')
      ->withoutGlobalScope('noEliminados');
  }

  public function cliente_with()
  {
    return $this->belongsTo( ClienteProveedor::class, 'PCcodi', 'PCCodi' )->withoutGlobalScope('noEliminados');
  }

  public function caja()
  {
    return $this->belongsTo( Caja::class, 'CajNume' , 'CajNume' );
  }
  
  public function caja_detalle()
  {
    return $this->belongsTo(CajaDetalle::class, 'CpaOper', 'DocNume');
  }

  public function proveedor()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCcodi', 'PCCodi')
      ->where('EmpCodi', $this->EmpCodi)
      ->where('TipCodi', 'P')
      ->withoutGlobalScope('noEliminados');
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'moncodi', 'moncodi');
  }

  public function forma_pago()
  {
    return $this->belongsTo(FormaPago::class, 'concodi', 'ConCodi');
  }

  public function pagos()
  {
    return $this->hasMany( CompraPago::class, 'CpaOper' , 'CpaOper' );
  }

  public function pagos_credito()
  {
    return $this->hasMany( CompraPago::class, 'CpaNCre', 'CpaOper');
  }

  public function medio_pago()
  {
    return $this->belongsTo(TipoPago::class, 'TpgCodi', 'TpgCodi');
  }

}