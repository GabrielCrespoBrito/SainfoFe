<?php 

namespace App\Models\Compra\Relationship;

use App\Caja;
use App\Moneda;
use App\Compra;
use App\TipoPago;
use App\CajaDetalle;
use App\ClienteProveedor;
use App\Control;

trait CompraPagoRelationship
{
  public function caja()
  {
    return $this->belongsTo(Caja::class, 'cajnume', 'CajNume');
  }

  public function cuenta()
  {
    return $this->caja->banco;
  }

  public function tipo_pago()
  {
    return $this->belongsTo(TipoPago::class, 'TpgCodi', 'TpgCodi');
  }

  public function nota_credito()
  {
    return $this->hasOne(Compra::class, 'CpaOper', 'CpaNCre' );
  }

  public function cliente()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi');
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'MonCodi', 'moncodi');
  }
  
  public function moneda_abbre()
  {
    return $this->moneda->monabre;
  }  

  public function compra()
  {
    return $this->belongsTo( Compra::class, 'CpaOper', 'CpaOper' );
  }

  public function caja_movimiento()
  {

    return $this->hasOne(CajaDetalle::class, 'DocNume', 'PagOper')
    ->where('CajNume', $this->cajnume )
    ->where('CtoCodi', Control::EGRESO_COMPRA );
  }

  public function caja_mov()
  {
    return $this->caja->detalles
    ->where('DocNume', $this->PagOper)
    ->where('OTRODOC', $this->CpaOper)
    ->where('CtoCodi', Control::EGRESO_COMPRA);
  }

}
