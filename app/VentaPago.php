<?php

namespace App;

use App\Moneda;
use Carbon\Carbon;
use App\ClienteProveedor;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\InteractWithFechas;
use App\Models\Traits\InteractWithMoneda;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class VentaPago extends Model
{
  use
  UsesTenantConnection,
  InteractWithMoneda,
  InteractWithFechas,
  ModelEmpresaScope;

	protected $table = "ventas_pago";
	protected $primaryKey = "PagOper";
	protected $keyType = "string";	
  public $incrementing = false;
  const ID_INIT = "000001";
  const EMPRESA_CAMPO = "EmpCodi";
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  public $fillable = ["vtaFpag" , 'PagImpo', 'CANINGS' , 'CANINGD' , 'CANEGRS', 'CANEGRD', 'CtoOper'];
  
  public function tipo_pago()
  {
  	return $this->belongsTo( TipoPago::class, 'TpgCodi' , 'TpgCodi' );
  }

  public function isBancario()
  {
    return (bool) $this->Bannomb;
  }

  public function getDocumento()
  {
    return $this->venta;
  }

  public function cliente()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi');
  }

  public function moneda()
  {
    return $this->belongsTo( Moneda::class, 'MonCodi' , 'moncodi' );
  }

  public function getMonedaNombre()
  {
    return Moneda::getAbrev($this->MonCodi);
  }

  /**
   * Si la moneda del padre del padre es la misma
   * 
   * @return bool
   */
  public function isSameMonedaParent() : bool
  {
    return $this->MonCodi === $this->venta->MonCodi;
  }

  /**
   * Obtener el importe total expresado en la moneda del padre
   * 
   * @return float
   */
  public function getRealImporte()
  {
    if($this->isSameMonedaParent() ){
      return $this->PagImpo;
    }

    return $this->isDolar() ?  ($this->PagImpo * $this->PagTCam) :  ($this->PagImpo / $this->PagTCam);
  }

  public function moneda_abbre()
  {
    return $this->moneda->monabre;
  }

  public function dec($value){
    return number_format((float)$value, 2, '.', '');
  }

  public function getPagImpoAttribute($value){
    return $this->attributes['PagImpo'] = $this->dec($value);    
  }

	public static function agregate_cero($numero=false,$set=0)
	{
		$numero = $numero ? $numero : self::ID_INIT;		
		$cero_agregar = [null,"00000","0000","000","00","0"];
		$codigoNum = ((int) $numero) + $set;
		$codigoLen = strlen((string) $codigoNum); 
		return $codigoLen < 8 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($codigoNum);
	}

	public function venta(){
		return $this->belongsTo( Venta::class, 'VtaOper' , 'VtaOper' );
	}

  public function registrarCaja()
  {
    CajaDetalle::registrarIngreso( $this ,  'caja' , $this->CajNume );    
  }

	public static function lastId()
	{
    $lastPagOper = self::max('PagOper');
    return  is_null($lastPagOper) ? agregar_ceros(0,6,1) :agregar_ceros( $lastPagOper,6,1);
	}

  public function isEfectivo()
  {
    return $this->TpgCodi == TipoPago::idEfectivo();
  }

  public function caja()
  {
    return $this->belongsTo(Caja::class, 'CajNume', 'CajNume');
  }

  public function caja_movimiento()
  {
    return $this->hasOne( CajaDetalle::class, 'DocNume' , 'PagOper' )
    ->where('CajNume', $this->CajNume)
    ->where('CtoCodi', Control::INGRESO_VENTA);
  }

  public function removeMovimiento()
  {
    $cajMov = $this->caja_movimiento;
    
    if ( ! $cajMov ) {
      return;
    }

    $caja = $cajMov->caja;

    if ( ! $caja ) {
      return;
    }


    if($caja->isAperturada()){
      $cajMov->delete();
    }

    else {            
      $cajMov->deleteState();
      $caja = Caja::currentCaja();
      CajaDetalle::eliminarVentaPago( $this, $caja );
    }

    $caja->calculateSaldo();
  }  


	public static function createPago($request)
	{
    set_timezone();
    $venta = Venta::find($request->VtaOper);    
    $tpgCodi = $request->tipopago;;
    $bancodi = "";
    $bannomb = "";
    $vtanume = $request->VtaNume;
    $cajaNume = Caja::currentCaja(null, get_empresa()->isTipoCajaLocal() )->CajNume;

    if ($request->cuenta_id) {
      $banco_cuenta = BancoEmpresa::find($request->cuenta_id);
      $bancodi = $banco_cuenta->BanCodi;
      $bannomb = $banco_cuenta->banco->bannomb;
      $vtanume = $request->baucher;
      $caja = Caja::cueCodi($request->cuenta_id)->ultima();
      $cajaNume = $caja->CajNume;
    }

    if( $request->tipopago == TipoPago::NOTACREDITO ){
      // $tpgCodi = TipoPago::NOTACREDITO;
    }

  	$ventaPago = new VentaPago; 
  	$ventaPago->PagOper = VentaPago::lastId();
  	$ventaPago->VtaOper = $request->VtaOper;
    $ventaPago->TpgCodi = $tpgCodi;
  	$ventaPago->PagFech = $request->input('fecha_pago', date('Y-m-d'));
  	$ventaPago->PagTCam = $request->tipocambio;  	
    $ventaPago->MonCodi = $request->moneda;
  	$ventaPago->PagImpo = $request->VtaImpo;
  	$ventaPago->BanCodi = $bancodi;
  	$ventaPago->Bannomb = $bannomb;
    $ventaPago->VtaNume = $vtanume;
    $ventaPago->VtaFVta = $venta->VtaFvta;
  	$ventaPago->VtaFVen = $venta->VtaFVen;
  	$ventaPago->PagBoch = $venta->VtaNume;
  	$ventaPago->usufech = date('Y-m-d');
  	$ventaPago->usuhora = date('H:m:i');
  	$ventaPago->usucodi = auth()->user()->usucodi;
  	$ventaPago->CajNume = $cajaNume;
  	$ventaPago->antnume = null;

    if( $request->tipopago === TipoPago::NOTACREDITO ){
      $ventaPago->CtoOper = $request->nota_credito_id;
    }

  	$ventaPago->CheP = null;
  	$ventaPago->ChECodi = null;
  	$ventaPago->PCCodi = $venta->PCCodi;
  	$ventaPago->VtaFact = 0;  	
  	$ventaPago->EmpCodi = $venta->EmpCodi;
  	$ventaPago->PanAno = $venta->PanAno;  	
  	$ventaPago->PanPeri = $venta->PanPeri;
    $venta->update([
      'vtaFpag' => date('Y-m-d')
    ]);
  	$ventaPago->User_Crea = auth()->user()->usulogi;  	  	
  	$ventaPago->User_ECrea = gethostname();
  	$ventaPago->save();
  	$venta->updatedDeuda();
    $ventaPago->updateDeudaNotaCredito();
    return $ventaPago;
  }

  /**
   * Actualizar la deuda de la nota de credito si es el caso
   * 
   * @return void
   */
  public function updateDeudaNotaCredito()
  {
    if( $this->isTipoCredito()  ){
      $this->nota_credito->updateDeudaByPagoNotaCredito();
    }
  }


  public function isTipoCredito()
  {
    return $this->TpgCodi == TipoPago::NOTACREDITO;
  }


  public  function nota_credito()
  {
    return $this->hasOne(Venta::class, 'VtaOper', 'CtoOper');
  }



  
  /**
   * Si el pago es en dolar 
   * 
   * @return bool
   */
  public function isDolar() : bool
  {
    return $this->MonCodi == Moneda::DOLAR_ID;
  }

  /**
   * Si el pago es un ingreso o un egreso
   * 
   * @TODO
   * @return bool
   */
  public function isIngreso(): bool
  {
    return true;
  }

  /**
   * Poner en 0 el pago
   * 
   * @return bool
   */
  public function anularPayment()
  {
    $caja = $this->caja;
    if( $caja->isAperturada() ){
      $this->update(['PagImpo' => 0]);
      $caja->calculateSaldo();
    }
    
    else {      
      $caja = Caja::currentCaja();
      CajaDetalle::eliminarVentaPago(  $this, $caja );
      $caja->calculateSaldo();
    }

  }

  /**
   * Actualizar información del movimiento de la caja
   * 
   * @return bool
   */
  public function updateMovimiento()
  {
    $mov = $this->caja_movimiento;
    
    if( is_null($mov) ){
      return;
    }

    $caja = $mov->caja;

    # Actualizar
    $isDolar = $this->isDolar();
    $isIngreso = $this->isIngreso();
    $monto = $this->PagImpo;

    $dataUpdate = [
      'CANINGS' => 0,
      'CANINGD' => 0,
      'CANEGRS' => 0,
      'CANEGRD' => 0,
    ];

    # Poner montos 
    if( $isIngreso ){
      $dataUpdate['CANINGS'] = $isDolar ? 0 : $monto;
      $dataUpdate['CANINGD'] = $isDolar ? $monto : 0;
    }
    else {
      $dataUpdate['CANEGRS'] = $isDolar ? 0 : $monto;
      $dataUpdate['CANEGRD'] = $isDolar ? $monto : 0;      
    }

    # Actualizar
    $mov->update($dataUpdate);
    $caja->calculateSaldo();
  }


  public function getDataFormat()
  {
    return [
      "id" => $this->PagOper,
      "fecha" => $this->PagFech,
      'tipopago' => $this->tipo_pago->descripcion,
      "moneda" => $this->moneda->monabre,
      "tcambio" => $this->PagTCam,
      "importe" => decimal($this->PagImpo),
      "modify" => 1,
      "delete" => 1,
    ];
  }

  /**
   * Actualizar información del pago
   *
   * @param $request
   * @return void
   */  
  public function updateInfo($request)
  {
    // Información de update      
    $dataUpdate = [
      'PagTCam' => $request->tipocambio,
      'MonCodi' => $request->moneda,
      'PagImpo' => $request->VtaImpo,
      'User_FModi' => date('Y-m-d'),
      'User_Modi' => auth()->user()->usucodi
    ];

    // Actualizar si es bancario
    if( $this->isBancario() ){
      $banco_cuenta = BancoEmpresa::find($request->cuenta_id);
      $dataUpdate['BanCodi'] = $banco_cuenta->BanCodi;
      $dataUpdate['Bannomb'] = $banco_cuenta->banco->bannomb;
      $dataUpdate['VtaNume'] = $request->baucher;
    }

    else if( $this->isTipoCredito() ) {
      $nota_credito_old = $this->nota_credito;
      $dataUpdate['CtoOper'] = $request->nota_credito_id;
    }
        
    $this->fill($dataUpdate);

    if( $isUpdate =  $this->isDirty(['PagTCam','MonCodi','PagImpo', 'BanCodi']) ){
      
      # Actualizar
      $this->removeMovimiento();
      $this->save();

      # Hacer el registro del ingreso
      $tipoIngreso = $this->isBancario()  ? 'banco' : 'venta';
      CajaDetalle::registrarIngreso($this, $tipoIngreso, $this->CajNume, $request->all());
      $this->venta->updatedDeuda();
    }
  

    if( $this->isTipoCredito() )
    {      
      $modifyNC = $nota_credito_old->VtaOper && $this->CtoOper;
      
      if( $modifyNC && ! $isUpdate  ){
        $this->save();
      }
      
      if( $modifyNC  ){
        $nota_credito_old->updateDeudaByPagoNotaCredito();
        $this->refresh()->nota_credito->updateDeudaByPagoNotaCredito();
      }

      else if($isUpdate) {
        $this->nota_credito->updateDeudaByPagoNotaCredito();
      }
    }
  }

  function docMismaCaja()
  {
    return $this->venta->CajNume == $this->CajNume;    
  }

  /**
   * Motivo de su registro en la caja de detalle
   * 
   * @return string
   */
  public function getMotivoDetalle()
  {
    return sprintf(
      "N.CREDITO DEL DIA %s %s",
      $this->venta->TidCodi,
      $this->venta->VtaNume
    );
  }

}