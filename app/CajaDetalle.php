<?php

namespace App;

use App\Jobs\Banco\RegisterIngreso;
use App\VentaPago;
use App\MotivoEgreso;
use App\MotivoIngreso;
use App\Models\Compra\CompraPago;
use App\Util\ModelUtil\ModelUtil;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Caja\Traits\CajaDetalleCompraTrait;

class CajaDetalle extends Model
{
  use
    ModelUtil,
    ModelEmpresaScope,
    UsesTenantConnection,
    CajaDetalleCompraTrait;

  protected $table = "caja_detalle";
  protected $primaryKey = "Id";
  protected $keyType = "string";
  public $incrementing = false;
  const CREATED_AT = 'User_FCrea';
  const EMPRESA_CAMPO = 'empcodi';
  const ESTADO_ANULADO = "A";

  const TIPO_INGRESO = "I";
  const TIPO_SALIDA = "S";

  const UPDATED_AT = 'User_FModi';
  public  $MocNumeCero = 6;
  public  $IdCero = 8;
  public $fillable = [
    "Id", "CueCodi", "MocNume", "CajNume", "DocNume", "MocFech", "MocFecV", "TIPMOV", "MocNomb", "CtoCodi", "MonCodi", "CtaImpo", "CtaDias", "CANINGS", "CANEGRS", "SALSOLE", "CtaSald", "CANINGD", "CANEGRD", "SALDOLA", "TIPCAMB", "FECANUI", "ANULADO", "CANMOV", "MOTIVO", "AUTORIZA", "OTRODOC", "LocCodi", "UsuCodi", "EgrIng", "PCCodi", "TDocCodi", "User_Crea", "User_FCrea", "User_ECrea", "User_Modi", "User_FModi", "User_EModi", "UDelete", "cheOper", "empcodi"
  ];

  public function motivo()
  {
    return $this->belongsTo(Control::class, 'CtoCodi', 'CtoCodi');
  }

  public function isTipoBanco()
  {
    return $this->isTipoCaja() == false;
  }

  public function isTipoCaja()
  {
    return $this->CueCodi == Caja::TIPOCAJA;
  }

  public function compra_pago()
  {
    return $this->belongsTo(CompraPago::class, 'DocNume', 'PagOper');
  }

  public function venta_pago()
  {
    return $this->belongsTo(VentaPago::class, 'DocNume', 'PagOper');
  }


  public function pago()
  {
    if ($this->CtoCodi == Control::EGRESO_COMPRA) {
      return $this->belongsTo(CompraPago::class, 'DocNume', 'PagOper');
    } else  {
      return $this->belongsTo(VentaPago::class, 'DocNume', 'PagOper');
    }
  }
  // venta_pago

  public function ingreso_soles()
  {
    return $this->CANINGS;
  }

  public function ingreso_dolar()
  {
    return $this->CANINGD;
  }

  public function isCaja()
  {
    if ($mot = $this->motivo) {
      return $mot->isCaja();
    }
    return false;
  }

  public function isIngresoVenta()
  {
    return $this->CtoCodi == Control::INGRESO_VENTA;
  }

  public function isIngreso()
  {
    return $this->ANULADO == 'I';
  }

  public function isEgreso()
  {
    return !$this->isIngreso();
  }

  public function isOtrosIngreso()
  {
    return $this->CtoCodi == Control::OTROS_INGRESOS;
  }

  public function isOtrosEgreso()
  {
    return $this->CtoCodi == Control::OTROS_EGRESOS;
  }

  public function isEgresoCompra()
  {
    return $this->CtoCodi == Control::EGRESO_COMPRA;
  }

  public function isOtrosIngresos()
  {
    return $this->isOtrosIngreso();
  }


  public function caja()
  {
    return $this->belongsTo(Caja::class, 'CajNume', 'CajNume');
  }

  public function getCANINGSAttribute($value)
  {
    return decimal($value);
  }
  public function getCANINGDAttribute($value)
  {
    return decimal($value);
  }

  public function getCANEGRSAttribute($value)
  {
    return decimal($value);
  }
  public function getCANEGRDAttribute($value)
  {
    return decimal($value);
  }

  public function setCANINGSAttribute($value)
  {
    $this->attributes['CANINGS'] = decimal($value);
  }
  
  public function setCANINGDAttribute($value)
  {
    $this->attributes['CANINGD'] = decimal($value);
  }

  public static function registrarApertura($caja)
  {
    $user = auth()->user();
    $model = new self;
    $data["Id"] = '';
    $data["CueCodi"] = "0000";
    $data["MocNume"] = "000001";
    $data["CajNume"] = $caja->CajNume;
    $data["MocFech"] = date('Y-m-d');
    $data["TIPMOV"]  = "CAJA";
    $data["MocNomb"] = "APERTURA CAJA";
    $data["CtoCodi"] = Control::CAJA;
    $data["CANINGS"] = $caja->CajSalS;
    $data["CANEGRS"] = 0;
    $data["SALSOLE"] = 0;
    $data["CANINGD"] = $caja->CajSalD;
    $data["TIPCAMB"] = TipoCambioPrincipal::ultimo_cambio(true);
    $data["CANEGRD"] = 0;
    $data["SALDOLA"] = 0;
    $data["ANULADO"] = CajaDetalle::TIPO_INGRESO;
    $data["MOTIVO"] = "APERTURA DE CAJA"; //    
    $data["AUTORIZA"] = $user->usulogi;
    $data["LocCodi"] = $caja->LocCodi;
    $data["UsuCodi"] = $user->usucodi;
    $data["empcodi"] = $caja->EmpCodi;
    $apertura = self::create($data);
  }


  public static function registrarEgresoCajaFromNotaCredito(VentaPago $venta_pago)
  {
    self::guardarDetalle(
      $venta_pago->PagTCam,
      '0000',
      $venta_pago->CajNume,
      $venta_pago->PagOper,
      date('Y-m-d'),
      'PAGO',
      $venta_pago->venta->cliente->PCNomb,
      Control::INGRESO_VENTA,
      $venta_pago->PagImpo,
      $venta_pago->MonCodi,
      $venta_pago->EmpCodi,
      $venta_pago->isSol() ? $venta_pago->PagImpo : 0,
      $venta_pago->isDolar() ? $venta_pago->PagImpo : 0,
      'S',
      0,
      0,
      $venta_pago->getMotivoDetalle(),
      $venta_pago->User_Crea,
      '',
      $venta_pago->LocCodi,
      $venta_pago->usucodi,
      $venta_pago->User_Crea
    );
  }

  public static function registrarEgresoCaja($data, $caja, $edit = false)
  {
    $nombre = optional(MotivoEgreso::find($data['motivo']))->Egrnomb;
    $caja2 = null;
    $user = user_();
    $c = new self();
    $egreso_tipo_id = $data['egreso_tipo'];
    $data_egreso = [];
    $data_egreso["Id"] = $c->getLastIncrement('Id');
    $data_egreso["empcodi"] = empcodi();
    $data_egreso["CueCodi"] = $caja->CueCodi;
    $data_egreso["DocNume"] = isset($data['DocNume']) ? $data['DocNume'] : '';
    $data_egreso["CajNume"] = $caja->CajNume;

    $data_egreso["MocFech"] = $data['fecha'];
    $data_egreso['MocFecV'] = $egreso_tipo_id == Control::TRANSFERENCIA_BANCO ? $data['fecha'] : null;
    $data_egreso["TIPMOV"]  = $egreso_tipo_id == Control::SALIDA_TRANSFERENCIA || $egreso_tipo_id == Control::ENTRADA_TRANSFERENCIA ? "TRANSFER" : "EGRESO";
    $data_egreso["MocNomb"] = $data['nombre'];
    $data_egreso["CtoCodi"] = $egreso_tipo_id;
    $data_egreso["MonCodi"] = $data["moneda"];

    if ($data['egreso_tipo'] == Control::ENTRADA_TRANSFERENCIA) {
      $data_egreso["CANINGS"] = $data['moneda'] == Moneda::SOL_ID ? $data['monto'] : 0;
      $data_egreso["CANINGD"] = $data['moneda'] != Moneda::SOL_ID ? $data['monto'] : 0;
    } else {
      $data_egreso["CANEGRS"] = $data['moneda'] == "01" ? $data['monto'] : 0;
      $data_egreso["CANEGRD"] = $data['moneda'] != "01" ? $data['monto'] : 0;
      $data_egreso["CANINGS"] = 0;
      $data_egreso["CANINGD"] = 0;
    }
    $data_egreso["SALSOLE"]    = 0;
    $data_egreso["SALDOLA"]    = 0;
    $data_egreso["TIPCAMB"]    = TipoCambioPrincipal::ultimo_cambio();
    $data_egreso["ANULADO"]    = $data['egreso_tipo'] == Control::ENTRADA_TRANSFERENCIA ? "I" : "S";
    $data_egreso["MOTIVO"]     = $nombre;
    $data_egreso["AUTORIZA"]   = $data['autoriza'];
    $data_egreso["OTRODOC"]    = $data['otro_doc'];
    $data_egreso["LocCodi"]    = $caja->LocCodi;
    $data_egreso["UsuCodi"]    = $user->usucodi;
    $data_egreso["EgrIng"]     = $data['egreso_tipo'] == Control::SALIDA_TRANSFERENCIA || $data['egreso_tipo'] == Control::ENTRADA_TRANSFERENCIA ? 999 : $data['motivo'];
    $data_egreso["PCCodi"]     = $data['egreso_tipo'] == Control::PAGO_PERSONAL ? $data['personal_id'] : null;
    $data_egreso["TDocCodi"]   = NULL;
    $data_egreso["User_Crea"]  = $user->usulogi;
    $data_egreso["User_ECrea"] = gethostname();
    $egreso = self::create($data_egreso);    
    $egreso->setMocCorrelative();
    
    // loremp-ipsum-odlor

    if ($egreso_tipo_id == Control::SALIDA_TRANSFERENCIA) {
      $data['egreso_tipo'] = Control::ENTRADA_TRANSFERENCIA;
      $data['nombre'] = "TRANSF. DESDE CAJA ({$caja->CajNume})";
      $caja2 = Caja::find($data['caja_transferencia']);            
      self::registrarEgresoCaja($data, $caja2);
    }

    else if ($egreso_tipo_id == Control::TRANSFERENCIA_BANCO) {
      (new RegisterIngreso($egreso, $data['banco_id']))->handle();
    }

    optional($caja)->calculateSaldo();
    optional($caja2)->calculateSaldo();
  }







  public static function registrarIngresoCaja($data, $caja, $tipo_mov = 'I')
  {
    $tipo = MotivoIngreso::find($data['motivo']);
    $tipoCodigo = $tipo->IngCodi;
    $tipoNombre = $tipo->IngNomb;
    $details = new CajaDetalle;
    $cajNume = $caja;
    $caja = Caja::find($caja);
    $user = auth()->user();
    $data["CANINGS"] = $data['moneda'] == "01" ? $data['monto'] : 0;
    $data["CANINGD"] = $data['moneda'] != "01" ? $data['monto'] : 0;
    $data["Id"] = $details->getLastIncrement('Id');
    $data["CueCodi"] = Caja::TIPOCAJA;
    $data["MocNume"] = self::MocNume(Control::OTROS_INGRESOS);
    $data["CajNume"] = $cajNume;
    $data["MocFech"] = isset( $data['fecha'] ) ? $data['fecha'] : date('Y-m-d');
    $data["MocFecV"] = NULL;
    $data["TIPMOV"]  = "INGRESO";
    $data["MocNomb"] = $data['nombre'];
    $data["CtoCodi"] = Control::OTROS_INGRESOS;
    $data["MonCodi"] = $data["moneda"];
    $data["CtaImpo"] = NULL;
    $data["CtaDias"] = NULL;
    $data["CANEGRS"] = 0;
    $data["SALSOLE"] = 0;
    $data["CtaSald"] = NULL;
    $data["CANEGRD"] = 0;
    $data["SALDOLA"] = 0;
    $data["TIPCAMB"] = TipoCambioPrincipal::ultimo_cambio();
    $data["FECANUl"] = NULL;
    $data["ANULADO"] = $tipo_mov;
    $data["CANMOV"] =  NULL;
    $data["MOTIVO"] = $tipoNombre;
    $data["AUTORIZA"] = $data['autoriza'];
    $data["OTRODOC"] = $data['otro_doc'];
    $data["LocCodi"] = $caja->LocCodi;
    $data["UsuCodi"] = $user->usucodi;
    $data["EgrIng"] = $tipoCodigo;
    $data["PCCodi"] = NULL;
    $data["TDocCodi"] = NULL;
    $data["User_Crea"] = $user->usulogi;
    $data["User_ECrea"] = gethostname();
    $data["empcodi"] = empcodi();
    self::create($data);
    optional($caja)->calculateSaldo();
  }


  public function method_name()
  {
    // EgrIng
  }



  public function setIdAttribute()
  {
    $this->attributes['Id'] = $this->getLastIncrement('Id');
  }

  public static function mocNume($CtoCodi, $tipo = 'I')
  {
    $caj_detalles = self::OrderByDesc('MocNume')->where('CtoCodi', $CtoCodi)->where('ANULADO', $tipo)->first();
    return is_null($caj_detalles) ? "000001" : agregar_ceros($caj_detalles->MocNume);
  }

  public function setMocCorrelative()
  {
    $filters = ['empcodi' => empcodi(), 'ANULADO' => $this->ANULADO, 'CueCodi' => $this->CueCodi, 'CajNume' => $this->CajNume];
    $mocnume = $this->getLastIncrement('MocNume', $filters);
    $this->update(['MocNume' => $mocnume]);
  }


  public static function registrarIngreso($info, $tipo, $caja, $pago = null, $tipo_mov = 'I')
  {
    set_timezone();
    $user = user_();

    $isIngreso = $tipo_mov == Caja::INGRESO;
    $campoSol = $isIngreso ? "CANINGS" : "CANEGRS";
    $campoDolar = $isIngreso ? "CANINGD" : "CANEGRD";
    $data = [];
    $data["TIPCAMB"] = $info->PagTCam;
    $data["CueCodi"] = "0000";
    $data["CajNume"]  = $caja;
    $data["DocNume"] =  $info->PagOper;
    $data["CANINGS"] =  0.00;
    $data["CANEGRS"] =  0.00;
    $data["CANINGD"] =  0.00;
    $data["CANEGRD"] =  0.00;

    $otrodoc = '';
    if ($tipo == 'caja') {

      $data["DocNume"] =  $info->VtaOper;
      $data["TIPMOV"]  = "CAJA";
      $data["MocNomb"] = "VENTA";
      $data["MOTIVO"] = "MOTIVO VENTA";
      $data[$campoDolar] = 0;
      $data[$campoSol] = $info->PagImpo;
    } 
    
    else if (strtolower($tipo) == "venta") {
      $data[$campoDolar] = 0;
      $data[$campoSol] = 0;
      $data["TIPMOV"]  = "VENTA";
      $data["MocNomb"] =  $info->venta->cliente->PCNomb;
      if ($info->docMismaCaja()) {
        $nombre = 'VENTA DEL DIA';
      } else {
        $nombre = "COBRANZA - {$info->venta->VtaFvta}";
      }
      $data["MOTIVO"] = $nombre;
    } 
    
    elseif ($tipo == "banco") {
      $data["MOTIVO"] = $info->venta->cliente->PCNomb . '- (' . $info->venta->TidCodi . ') ' . $info->venta->VtaNume;
      $data["TIPMOV"]  = "PAGO";
      $banco = Caja::cueCodi($pago['cuenta_id'])->ultima();
      $cuenta = $banco->banco;
      $data["CueCodi"] = $pago['cuenta_id'];
      $data["CajNume"] = $banco->CajNume;
      $otrodoc = $pago['baucher'];
    } 
    
    
    else {
    }

    $data[$campoDolar] = $info->isDolar() ? $info->PagImpo : 0;
    $data[$campoSol] = $info->isDolar() ? 0 : $info->PagImpo;


    if ($tipo == "banco") {
      if ($cuenta->MonCodi != $info->MonCodi) {
        if ($info->MonCodi != $cuenta->MonCodi) {
          if ($cuenta->isSol()) {
            $data[$campoSol] = $data[$campoDolar] * $info->PagTCam;
            $data[$campoDolar]  = 0;
          } else {
            $data[$campoDolar] = $data[$campoSol] / $info->PagTCam;
            $data[$campoSol] = 0;
          }
        }
      }
    }

    $ca = Caja::find($caja);
    $data["Id"] = null;
    $data["MocFech"] = date('Y-m-d');
    $data["MocFecV"] = NULL;
    $data["CtoCodi"] = Control::INGRESO_VENTA;
    $data["CtaImpo"] = NULL;
    $data["CtaDias"] = NULL;
    $data["MonCodi"] = $info->MonCodi;
    $data["empcodi"] = empcodi();
    // $data["CANEGRS"] = 0;
    $data["SALSOLE"] = 0;
    $data["CtaSald"] = NULL;
    // $data["CANEGRD"] = 0;
    $data["SALDOLA"] = 0;
    $data["FECANUl"] = NULL;
    $data["ANULADO"] = $tipo_mov;
    $data["CANMOV"] =  NULL;
    $data["AUTORIZA"] = $user->usulogi;
    $data["OTRODOC"] = $otrodoc;
    $data["LocCodi"] = $user->local();
    $data["UsuCodi"] = $user->usucodi;
    $data["EgrIng"] = NULL;
    $data["PCCodi"] = NULL;
    $data["TDocCodi"] = NULL;
    $data["User_Crea"] = $user->usulogi;
    $data["User_ECrea"] = gethostname();
    $caja_detalle = self::create($data);
    optional($ca)->calculateSaldo();
    $caja_detalle->setMocCorrelative();
  }

  public function fullDelete()
  {
  }

  public function anular()
  {
    return $this->update([
      "CANINGS" => 0,
      "CANINGD" => 0,
      "CANEGRS" => 0,
      "CANEGRD" => 0,
    ]);
  }

  /**
   * Cliente de ingreso 
   * 
   * @return mixed
   */

  public function cliente()
  {
    return optional($this->venta_pago)->cliente;
  }

  /**
   * Si el movimiento de caja corresponde a un pago de compra o venta, osea tiene DocNume
   * 
   * @return bool
   */
  public function isDocPago()
  {
    return  $this->DocNume != null;
  }


  /**
   * Cliente de ingreso 
   * 
   * @return mixed
   */

  public function documentoRef()
  {

    if (!$this->isDocPago()) {
      return null;
    }

    return $this->isIngresoVenta() ? optional($this->venta_pago)->PagBoch : optional($this->compra_pago)->CpaNume;
  }

  /**
   * En el caso de reporte mostrar el documento si es una pago o el correlativo de la caja
   * 
   * @return mixed
   */
  public function documentoShowReporte()
  {
    if ($this->CtoCodi == Control::EGRESO_COMPRA) {
      return optional($this->compra_pago)->CpaNume;
    } else if ($this->CtoCodi == Control::INGRESO_VENTA) {
      return optional($this->venta_pago)->PagBoch;
    }

    return $this->MocNume;
  }


    public function documentoName()
  {
    if ($this->CtoCodi == Control::EGRESO_COMPRA) {
      return optional($this->pago)->CpaNume;
    } else if ($this->CtoCodi == Control::INGRESO_VENTA) {
      return optional($this->pago)->PagBoch;
    }

    return $this->MocNume;
  }


  /**
   * En el reporte información del cliente
   * 
   * @return mixed
   */
  public function clienteShowReporte()
  {
    if (null == $name = optional($this->cliente())->PCNomb) {
      $name =  $this->OTRODOC . ' ' . $this->MocNomb;
    }

    return $name;
  }

  public function cleanMontos()
  {
    $this->update([
      'CANINGS' => 0,
      'CANINGD' => 0,
      'CANEGRS' => 0,
      'CANEGRD' => 0,
    ]);
  }

  public function deleteState()
  {
    $pago = $this->pago;

    if (!$pago) {
      return;
    }

    if (!$venta = $pago->venta) {
      return;
    }

    $this->MOTIVO = 'Pago Elim. ' . $venta->TidCodi . "-" . $venta->VtaSeri . '-' . $venta->VtaNumee;
    $this->ANULADO = self::ESTADO_ANULADO;
    $this->save();
  }

  /**
   * Poner el tipo de cambio si es en dolar
   * 
   * @return numeric
   */
  public function tipoCambioIsNeeded()
  {
    return $this->MonCodi === Moneda::DOLAR_ID ? $this->TIPCAMB : 0;
  }

  /**
   * Tipo de Ingreso del movimiento
   * 
   * @return belongsTo
   */
  public function tipoIngreso()
  {
    return $this->belongsTo(MotivoIngreso::class,  'EgrIng', 'IngCodi');
  }

  /**
   * Tipo de Egreso del movimiento
   * 
   * @return belongsTo
   */
  public function tipoEgreso()
  {
    return $this->belongsTo(MotivoEgreso::class,  'EgrIng', 'EgrCodi');
  }

  public function guardarCompra($compra_pago, $eliminar = false)
  {
    // return self::guardarDetalle(
    //   $tc,
    //   $cuecodi,
    //   $caja_id,
    //   $docnume,
    //   $mocfecha,
    //   $tipo_mov,
    //   $nombre,
    //   $control,
    //   $ctaimpo,
    //   $moncodi,
    //   $empcodi,
    //   $egreso_sol,
    //   $egreso_dolar,
    //   $anulado,
    //   $insgreso_sol,
    //   $ingreso_dolar,
    //   $motivo,
    //   $autoriza,
    //   $otrodoc,
    //   $local,
    //   $usucodi,
    //   $usulogi
    // );
  }

  /**
   * 
   * Guardar la información de un detalle
   * @param float $tc = Tipo de cambio
   * @return App\CajaDetalle;
   */
  public static function guardarDetalle(
    $tc,
    $cuecodi,
    $caja_id,
    $docnume,
    $mocfecha,
    $tipo_mov,
    $nombre,
    $control,
    $ctaimpo,
    $moncodi,
    $empcodi,
    $egreso_sol,
    $egreso_dolar,
    $anulado,
    $insgreso_sol,
    $ingreso_dolar,
    $motivo,
    $autoriza,
    $otrodoc,
    $local,
    $usucodi,
    $usulogi
  ) {
    $data = array();
    $data["Id"] = null;
    $data["TIPCAMB"] = $tc;
    $data["CueCodi"] = $cuecodi;
    $data["CajNume"]  = $caja_id;
    $data["DocNume"] =  $docnume;
    $data["MocFech"] = $mocfecha;
    $data["TIPMOV"]  = $tipo_mov;
    $data["MocNomb"] = $nombre;
    $data["MocFecV"] = NULL;
    $data["CtoCodi"] = $control;
    $data["CtaImpo"] = $ctaimpo;
    $data["CtaDias"] = NULL;
    $data["MonCodi"] = $moncodi;
    $data["empcodi"] = $empcodi;
    $data["CANEGRS"] = $egreso_sol;
    $data["SALSOLE"] = 0;
    $data["CtaSald"] = NULL;
    $data["CANEGRD"] = $egreso_dolar;
    $data["SALDOLA"] = 0;
    $data["FECANUl"] = NULL;
    $data["ANULADO"] = $anulado;
    $data["CANMOV"] =  NULL;
    $data["CANINGS"] = $insgreso_sol;
    $data["CANINGD"]  = $ingreso_dolar;
    $data["MOTIVO"] = $motivo;
    $data["AUTORIZA"] = $autoriza;
    $data["OTRODOC"] = $otrodoc;
    $data["LocCodi"] = $local;
    $data["UsuCodi"] = $usucodi;
    $data["EgrIng"] = NULL;
    $data["PCCodi"] = NULL;
    $data["TDocCodi"] = NULL;
    $data["User_Crea"] = $usulogi;
    $data["User_ECrea"] = gethostname();
    $caja_detalle = self::create($data);
    $caja_detalle->setMocCorrelative();
    $caja_detalle->caja->calculateSaldo();
  }

  public function deleteFull()
  {
    $this->delete();
  }

  public function isSol()
  {
    return Moneda::isSol($this->MonCodi);
  }

  public function getMonto()
  {
    if ($this->isIngreso()) {
      return $this->isSol() ?  $this->CANINGS : $this->CANINGD;
    } else {
      return $this->isSol() ? $this->CANEGRS : $this->CANEGRD;
    }
  }

  public function getDataForPDF()
  {
    $empresa = get_empresa();

    return  [
      'nombre' => $this->MocNomb,
      'cantidad' =>  sprintf('%s %s', Moneda::getAbrev($this->MonCodi), $this->getMonto()),
      'motivo' => $this->MOTIVO,
      'documento' => $this->OTRODOC,
      'autoriza' => $this->AUTORIZA,
      'fecha' => $this->User_FCrea,
      'direccion' => $empresa->direccion(),
    ];
  }
}
