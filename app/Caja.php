<?php

namespace App;

use App\Control;
use App\CajaDetalle;
use App\Jobs\Caja\MovimientosData;
use App\Jobs\Caja\ReporteCompraVentaData;
use App\Models\Caja\Method\CajaMethod;
use App\Models\Caja\Relationship\CajaRelationship;
use App\Models\Caja\Scope\CajaScope;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
  use
  UsesTenantConnection,
  CajaMethod,
  CajaScope,
  CajaRelationship,
  ModelEmpresaScope;

	protected $table = "caja";	
	protected $primaryKey = "CajNume";
	protected $keyType = "string";	
  public $incrementing = false;
  const  EMPRESA_CAMPO = "EmpCodi";
  const LOCAL_DEFAULT = "001";
  const CREATED_AT = 'User_FCrea';
  const UPDATED_AT = 'User_FModi';
  const INGRESO = 'I';
  const EGRESO = 'S';
  const ESTADO_APERTURADA = 'Ap';
  const ESTADO_CERRADA = 'Ce';
  const TIPOCAJA = "0000";

  public $fillable = ["CajEsta", "CajSalS" , "CajSalD"];
  
  public function user()
  {
    return $this->belongsTo(User::class, 'UsuCodi' , 'UsuCodi');
  }

  public function apertura()
  {
    return $this->detalles->where('CtoCodi', Control::CAJA )->first();
  }

  /**
   * Data detalle
   * 
   */
  public function dataReporte()
  {
    // $detalles = Caja::detallesByTipo($this->CajNume);
    $detalles = $this->detalles->load('venta_pago');

    #      Ingresos totales
    $total_ingreso_sol = $detalles->where('ANULADO', self::INGRESO )->sum('CANINGS');
    $total_egreso_sol = $detalles->where('ANULADO', self::EGRESO )->sum('CANEGRS');    
    $total_ingreso_dolar = $detalles->where('ANULADO', self::INGRESO)->sum('CANINGD');
    $total_egreso_dolar = $detalles->where('ANULADO', self::EGRESO)->sum('CANEGRD');

    # Saldo inicial
    $detalle_apertura = $this->apertura();

    $saldo_inicial_sol = decimal(optional($detalle_apertura)->CANINGS);
    $saldo_inicial_dolar = decimal(optional($detalle_apertura)->CANINGD);    
    
    $items = [];
    foreach( $detalles as $detalle )
    {
      if( $detalle->CtoCodi === Control::CAJA ){
        continue;
      }
      
      $item_data = [
        'nro_documento' => $detalle->documentoName() ,
        'motivo' =>  $detalle->MOTIVO,
        'nombre' =>  $detalle->MocNomb,
        'ingreso_sol' => $detalle->CANINGS,
        'egreso_sol' =>  $detalle->CANEGRS,
        'fecha' =>  optional($detalle->venta_pago)->VtaFVta ?? $detalle->MocFech,
        'tipo_cambio'   => $detalle->tipoCambioIsNeeded(),
        'ingreso_dolar' =>  $detalle->CANINGD,
        'egreso_dolar' => $detalle->CANEGRD,
      ];
      array_push( $items , $item_data);
    }

    $saldo_final_sol = decimal($total_ingreso_sol - $total_egreso_sol);
    $saldo_final_dolar = decimal( $total_ingreso_dolar - $total_egreso_dolar);

    return 
    [
      'caja' => $this,
      'items' => $items,
      'saldo_inicial_sol'  => $saldo_inicial_sol,
      'saldo_inicial_dolar'=> $saldo_inicial_dolar,
      'total_ingreso_sol'  => $total_ingreso_sol,
      'total_egreso_sol'   => $total_egreso_sol,
      'total_ingreso_dolar'=> $total_ingreso_dolar,
      'total_egreso_dolar' => $total_egreso_dolar,
      'saldo_final_sol'    => $saldo_final_sol,
      'saldo_final_dolar'  => $saldo_final_dolar,
    ];
  }


  public function getIngresosEgresos(){   
    $caja = $this;

    $detalles = Caja::detallesByTipo($this->CajNume);
    $ingresos = $detalles->where( 'ANULADO' , "I" );
    $egresos = $detalles->where( 'ANULADO'  , "S" );
    
    return [ 'ingresos' => $ingresos , 'egresos' => $egresos ];
  }

  public function data_reportes()
  {
    $data = $this->getIngresosEgresos();
    $caja = $this;
    $ingresos = $data['ingresos'];
    $egresos = $data['egresos'];    
    
    $total_ingresos_soles = fixedValue($ingresos->sum('CANINGS'));
    $total_ingresos_dolar = fixedValue($ingresos->sum('CANINGD'));
    $total_egresos_soles = fixedValue($egresos->sum('CANEGRS'));
    $total_egresos_dolar = fixedValue($egresos->sum('CANEGRD'));

    $ventas_grupo = \DB::connection('tenant')->table('ventas_cab')
    ->where('ventas_cab.CajNume', '=' , $caja->CajNume   )
    ->select('ventas_cab.*')
    ->get()
    ->groupBy('TidCodi');

    $compras_grupo = \DB::connection('tenant')->table('compras_cab')
    ->where('compras_cab.CajNume', '=', $caja->CajNume)
    ->select('compras_cab.*')
    ->get()
    ->groupBy('TidCodi');


    $ventas_data = [
      '01' => [ "0.00", "0.00"   ],
      '03' => [ "0.00", "0.00"   ],
      '07' => [ "0.00", "0.00"   ],
      '08' => [ "0.00", "0.00"   ],      
      TipoDocumentoPago::NOTA_VENTA => [ "0.00", "0.00"   ],
      'total' => [ "0.00", "0.00"],      
    ];

    $compras_data = [
      '01' => ["0.00", "0.00"],
      '03' => ["0.00", "0.00"],
      '07' => ["0.00", "0.00"],
      '08' => ["0.00", "0.00"],
      TipoDocumentoPago::NOTA_VENTA => ["0.00", "0.00"],
      'total' => ["0.00", "0.00"],
    ];    

    $tipos_permitidos = [
      "01",  "03", "07", "08", TipoDocumentoPago::NOTA_VENTA
    ];

    foreach($ventas_grupo as $key => $ventas ){
      if( in_array( $key  , $tipos_permitidos ) ){
        $soles = fixedValue($ventas->where('MonCodi','01')->sum('VtaImpo'));
        $dolar = fixedValue($ventas->where('MonCodi','02')->sum('VtaImpo'));
        $ventas_data[$key][0] = $soles;
        $ventas_data[$key][1] = $dolar;
        $ventas_data["total"][0] += $soles;
        $ventas_data["total"][1] += $dolar;
      }
    }

    foreach ($compras_grupo as $key => $compras) {
      if (in_array($key, $tipos_permitidos)) {
        $soles = fixedValue($compras->where('moncodi', '01')->sum('Cpatota'));
        $dolar = fixedValue($compras->where('moncodi', '02')->sum('Cpatota'));
        $compras_data[$key][0] = $soles;
        $compras_data[$key][1] = $dolar;
        $compras_data["total"][0] += $soles;
        $compras_data["total"][1] += $dolar;
      }
    }  

    return compact(
      'caja', 
      'ingresos',
      'egresos',
      'total_ingresos_dolar',
      'total_ingresos_soles',
      'total_egresos_dolar',
      'total_egresos_soles',
      'ventas_data',
      'compras_data'
    );
  }

  public function calculateSaldo(){

    $data = $this->getIngresosEgresos();
    $ingresos = $data['ingresos'];
    $egresos = $data['egresos'];    
    $total_ingresos_soles = decimal($ingresos->sum('CANINGS'));
    $total_ingresos_dolar = decimal($ingresos->sum('CANINGD'));
    $total_egresos_soles = decimal($egresos->sum('CANEGRS'));
    $total_egresos_dolar = decimal($egresos->sum('CANEGRD'));   
    $saldoSoles = fixedValue($total_ingresos_soles - $total_egresos_soles);
    $saldoDolar = fixedValue($total_ingresos_dolar - $total_egresos_dolar);
    $this->update([
    "CajSalS" => $saldoSoles,
    "CajSalD" => $saldoDolar, 
    ]);
  }

  public function isAperturada()
  {
    return $this->CajEsta == "Ap";
  }

  public function isCerrada()
  {
    return !$this->isAperturada();
  }

  public function detalles()
  {
    return $this->hasMany( CajaDetalle::class, 'CajNume' , 'CajNume' );
  }

  public static function cajaAperturada( $loccodi = null, $onlyLocal = false )
  {
    $loccodi = $loccodi ?? optional(user_()->localCurrent())->loccodi;


    $conditions = [
      'LocCodi'  => $loccodi,
      'CajEsta'  => 'Ap',
      'CueCodi'  => self::TIPOCAJA
    ];
    
    if( !$onlyLocal ) {
      $conditions['UsuCodi'] = user_()->usucodi;
    }


    return self::where($conditions);        
  }

  public static function currentCaja($loccodi = null, $onlyLocal = false )
  {
    return self::cajaAperturada($loccodi , $onlyLocal)->first();
  }


  public static function hasAperturada( $loccodi = null, $onlyLocal = false )
  {
    return self::cajaAperturada($loccodi, $onlyLocal)->count();
  }

  public static function AperturadasEmpresa( $local_default = self::LOCAL_DEFAULT )
  {
    return self::where([
      'EmpCodi'  => get_empresa('empcodi'),
      'LocCodi'  => $local_default ,
      'CajEsta'  => 'Ap' 
    ])->get();
  }


  public function reaperturar(){
    $this->CajEsta = "Ap";
    $this->save();
  }


  public function tiene_movimientos(){    
    return $this->detalles->where('CtoCodi', '!=' , Control::CAJA )->count();
  }

  public function estaCerrada(){
    return $this->CajEsta != "Ap";
  }

  public static function getcajNume( $request, $banco )
  {
    if( $banco ){
      return  $request->cuenta_id . $request->periodo_id;
    }
    
    set_timezone();
    $user = user_();
    $empcodi = empcodi();
    $usucodi = $user->usucodi;
    $loccodi = $request->id_local;

    $cajas = self::where([
      'EmpCodi' => $empcodi,
      'LocCodi' => $loccodi,
      'UsuCodi' => $usucodi,
      'CueCodi' => self::TIPOCAJA
    ]);

    $cajNume = $cajas->max('CajNume');

    // No hay cajas existentes de este usuario en ese local y con esa empresa
    if( is_null($cajNume) ){
      $codigo_inicial =  substr($loccodi,-1);      
      $cajNume = sprintf('%s%s-%s', $codigo_inicial , $usucodi, '000001' );
    }

    

    // Existen otras cajas de ese usuario
    else {
      $nume = explode("-", $cajNume);      
      $primera_parte = $nume[0];
      $segunda_parte = agregar_ceros( $nume[1] );
      $cajNume = $primera_parte . "-" . $segunda_parte;
    }

    return $cajNume;
  }

  public static function lastCaja($usucodi, $loccodi)
  {
    return Caja::usucodi($usucodi)
    ->locCodi($loccodi)
    ->orderByDesc('CajNume')
    ->first();
  }


  public static function Aperturar($request,  $banco = false )
  {
    $local = $banco ? user_()->localCurrent()->loccodi : $request->id_local;
    $mescodi = $banco ? $request->periodo_id : date('Ym');
    $user = user_();

    $saldoSol = 0.00;
    $saldoDolar = 0.00;

    if( ! $banco ){
      $lastCaja = Caja::lastCaja($user->usucodi, $local);
      if( $lastCaja ){
        $saldoSol = $lastCaja->CajSalS;
        $saldoDolar = $lastCaja->CajSalD;
      }
    }
    
    $cuenta =  $banco ? $request->cuenta_id : "0000";
    $caja = new Caja;
    $caja->CajNume = self::getcajNume( $request, $banco );
    $caja->CueCodi = $cuenta;
    $caja->CajFech = date('Y-m-d');    
    $caja->CajSalS = $saldoSol;
    $caja->CajSalD = $saldoDolar;
    $caja->CajEsta = "Ap";    
    $caja->UsuCodi = $user->usucodi;
    $caja->CajHora = "00:00:00";           
    $caja->LocCodi = $local; 
    $caja->EmpCodi = empcodi();               
    $caja->PanAno  = date('Y');               
    $caja->PanPeri = date('m');               
    $caja->MesCodi = $mescodi;
    $caja->User_Crea  = $user->usulogi;
    $caja->User_ECrea = gethostname();
    $caja->save();

    CajaDetalle::registrarApertura($caja);
  }

  public function cerrar()
  {
    $this->CajEsta = "Ce";
    $this->CajFecC = date('Y-m-d');
    $this->save();
  }  

  public function ingresos()
  {
    return $this->detalles->where('CtoCodi' , '003')->all();
  }

  public function egresos(){
    return $this->detalles->where('CtoCodi' , 'EGRESO')->all();
  }  

  public function eliminar()
  {
    foreach( $this->detalles as $detalle ){
      $detalle->delete();
      
    }
    $this->delete();
  }

  public static function urlMovimientoAccion( $accion , $tipo_movimiento , $id_caja )
  {
    if(  $accion != "crear" && $accion != "modificar" && $accion != "eliminar"  ){
      throw new \Exception("Las acciones son crear, modificar y eliminar");
    }

    //  Crear
    if(  $accion == "crear" ){
      // Caja
      if(Control::CAJA == $tipo_movimiento ){
        return route('cajas.dinero_apertura' , $id_caja );
      }
      //  Otros ingresos
      if( in_array($tipo_movimiento, Control::INGRESOS) ){
        return route('cajas.ingresos_create' , $id_caja );
      }
      //  Otros ingresos
      if( in_array($tipo_movimiento, Control::EGRESOS) ){
        return route('cajas.egresos_create' , $id_caja );
      }
    }
    // Modificar
    else if( $accion == "modificar" ){      
      // Ingresos-
      if( in_array($tipo_movimiento, Control::INGRESOS) ){
        return route('cajas.ingresos_edit' , $id_caja );
      }
      //  Otros ingresos
      if( in_array($tipo_movimiento, Control::EGRESOS) ){
        return route('cajas.egresos_edit' , $id_caja );
      }
    }

    // Eliminar
    else {
      return route('cajas.borrar_movimiento' , $id_caja );
    }
  }

  public function getEstadoAttribute()
  {
    return $this->isAperturada() ? 'APERTURADO' : 'CERRADO';
  } 

  public function getDataReporteCompraVenta($tipo = "ventas", $agrupacion = "tipo_documento")
  {
    $reporteData = new ReporteCompraVentaData($this, $tipo, $agrupacion);

    return $reporteData->getData();
  }

  public function getDataReporteVenta()
  {
    $empresa = $this->empresa;
    $groupDocs = $this->ventas->groupBy('TidCodi');
    $items = [
      'totales' => [
        'importe' => 0,
        'pago' => 0,
        'saldo' => 0        
      ]
    ];

    foreach( $groupDocs as $tipo => $docs ){

      $items['tipos'][$tipo] = [
        'docs' => [],
        'total' => [
          'importe' => 0,
          'pago' => 0,
          'saldo' => 0,
          'items' => 0
        ],
      ];

      foreach( $docs as $doc ){

        $importe = $doc->importe();
        $pago = $doc->pago();
        $saldo = $doc->saldo();

        $data = [
          'nroDoc' => $doc->numero(),
          'fechaEmision' =>$doc->fechaEmision(),
          'docRef' => $doc->documentoReferencia(),
          'clienteRazonSocial' => strtoupper($doc->clienteRazonSocial()),
          'estado' => $doc->estado(),
          'moneda' => $doc->monedaAbbreviatura(),
          'importe' => $importe,
          'pago' => $pago,
          'saldo' => $saldo,
          'condicion' => $doc->condicion(),
        ];    
        // Agregar Columna
        $items['tipos'][$tipo]['docs'][] = $data;

        // Totales
        $items['tipos'][$tipo]['total']['importe'] +=  $importe;
        $items['tipos'][$tipo]['total']['pago'] += $pago;
        $items['tipos'][$tipo]['total']['saldo'] += $saldo;

        // Sumar tambien a los totales globales
        $items['totales']['importe'] += $importe;
        $items['totales']['pago'] += $pago;
        $items['totales']['saldo'] += $saldo;
      }

      $items['tipos'][$tipo]['total']['items'] = count($docs);
    }

    return [
      'empresaNombre' => $empresa->EmpNomb,
      'empresaRuc' => $empresa->ruc(),
      'fechaApertura' => $this->CajFech,
      'fechaCierre' => $this->CajFecC,
      'cajaNumero' => $this->CajNume,
      'fechaReporte' => date('Y-m-d m:h:s'),
      'items'  => $items,
    ];
  }

  public function getDataReporteCompra()
  {
    return [];
  }

  public static function detallesByTipo( $id_caja, $tipo_movimiento = null, $exclude_tipo_nota_credito = true )
  {
    $relacion = $tipo_movimiento == 'I' ? 'tipoIngreso' : 'tipoEgreso';

    $movimientos = CajaDetalle::with(['venta_pago', $relacion])
    ->where('CajNume', $id_caja);

    if( $tipo_movimiento ){
      $movimientos->where('ANULADO', $tipo_movimiento );
    }

    return $movimientos->get();

  }


  public function isTipoBanco()
  {
    return $this->isTipoCaja() == false;
  }

  public function isTipoCaja()
  {
    return $this->CueCodi == Caja::TIPOCAJA;
  }

  public function bancoCuenta()
  {
    return $this->belongsTo( BancoEmpresa::class , 'CueCodi', 'CueCodi' );
  }

  public function getDataMovimientos()
  {
    return (new MovimientosData($this))->handle();
  }


}