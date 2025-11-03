<?php

namespace App;

use App\SerieDocumento;
use App\Helpers\FHelper;
use App\Jobs\Venta\GetFiles;
use App\Traits\InteractWhatApp;
use Illuminate\Support\Facades\DB;
use App\Helper\PDFDirect\PDFDirect;
use App\Jobs\Empresa\ImgStringInfo;
use App\Jobs\Venta\AnularDocumento;
use App\Models\GuiaVenta\GuiaVenta;
use App\ReportData\VentaReportData;
use Illuminate\Support\Facades\Log;
use App\Models\Venta\Traits\Inventary;
use App\Util\PDFGenerator\PDFGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\InteractPlantilla;
use App\Models\Venta\Method\VentaMethod;
use App\Models\Traits\InteractWithMoneda;
use App\Models\Venta\Traits\VentaReporte;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Models\Traits\InteractWithPayment;
use App\Jobs\Venta\UpdateProductosEnviados;
use App\Models\Venta\Traits\CalculatorTotal;
use App\Models\Venta\Traits\VentaCalculates;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Venta\Attribute\VentaAttribute;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Util\XmlInformation\XmlInformationResolver;
use App\Models\Venta\Relationship\VentaRelationship;
use App\Util\GenerateResumenAnulacion\GenerateResumenAnulacion;

class Venta extends Model
{
  use
    Inventary,
    UsesTenantConnection,
    VentaMethod,
    InteractWhatApp,
    VentaAttribute,
    VentaCalculates,
    VentaReporte,
    ModelEmpresaScope,
    VentaRelationship,
    InteractWithPayment,
    InteractWithMoneda,
    InteractPlantilla,
    XmlInformationResolver;

  const FORMATO_A4 = 'a4';
  const FORMATO_A5 = 'a5';
  const FORMATO_TICKET = 'ticket';

  const GUIA_ACCION_NINGUNA = 'sin_guia';
  const GUIA_ACCION_INTERNA = 'nueva_interna';
  const GUIA_ACCION_ELECTRONICA = 'nueva_electronica';
  const GUIA_ACCION_TRANSPORTISTA = 'transportista';
  const GUIA_ACCION_ASOCIAR = 'asociar';

  const CARGO_DESCUENTO = 1;
  const CARGO_PERCEPCION = 2;
  const CARGO_RETENCION = 3;

  // 
  const TIPO_CANJEADA = 'C';
  const TIPO_CANJEADOR = 'F';
  const TIPO_NORMAL = 'N';

  public function __construct()
  {
    parent::__construct();
    $this->calculator = new CalculatorTotal();
  }

  protected $table = "ventas_cab";
  protected $primaryKey = "VtaOper";
  protected $keyType = "string";
  public $timestamps = true;
  public $casts = [
    'CuenCodi' => 'array'
  ];
  public $incrementing = false;
  public $calculator;
  public $totales_items = [];

  /**
   * Calculos generados 
   */
  public $totales;


  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  const ID_INIT = "000000";

  // Tipos de afectación
  const GRAVADA = 'GRAVADA';
  const INAFECTA = 'INAFECTA';
  const GRATUITA = 'GRATUITA';
  const EXONERADA = 'EXONERADA';

  const FACTURA = "01";
  const BOLETA = "03";
  const NOTA_CREDITO = "07";
  const NOTA_DEBITO = "08";
  const NOTA_VENTA = "52";

  const FE_RPTA_0 = 0;
  const FE_RPTA_2 = 2;
  const FE_RPTA_9 = 9;

  public $fillable = [
    "VtaOper",
    "EmpCodi",
    "PanAno",
    "PanPeri",
    "TidCodi",
    "VtaSeri",
    "VtaNume",
    "VtaNumee",
    "VtaUni",
    "VtaFvta",
    "PCCodi",
    "vtaFpag",
    "VtaFVen",
    "Vtacant",
    "Vtabase",
    "VtaIGVV",
    "VtaDcto",
    "VtaInaf",
    'MesCodi',
    'VtaSald',

    "VtaTDR",
    "VtaNumeR",
    "VtaSeriR",
    "VtaFVtaR",

    "TipoIGV",
    'User_Crea',
    'User_ECrea',
    'LocCodi',
    'UsuCodi',
    'vtafact',
    'fe_rptaa',
    "TpgCodi",
    'VtaMail',
    "TipCodi",
    "VtaExon",
    "VtaGrat",
    'VtaSdCa',
    "VtaISC",
    "VtaImpo",
    "AlmEsta",
    "CajNume",
    "TipoOper",
    'VtaOperC',
    "VtaEsta",
    "VtaPago",
    'TipoOper',
    "VtaTotalAnticipo",
    'VtaFMail',
    'Vencodi',
    'ConCodi',
    'VtaEsPe',
    'ZonCodi',
    'MonCodi',
    "VtaPPer",
    "GuiOper",
    "VtaAPer",
    "VtaPerc",
    "VtaTota",
    "VtaSPer",
    'VtaTcam',
    'VtaHora',
    'VtaObse',
    "VtaCDR",
    "CuenCodi",
    "VtaXML",
    "VtaPDF",
    "fe_rpta",
    "fe_obse",
    "fe_estado",
  ];

  public function isSol()
  {
    return $this->MonCodi === Moneda::SOL_ID;
  }

  public static function boot()
  {
    parent::boot();

    static::creating(function (Venta $venta) {
      $venta->VtaUni =  sprintf('%s-%s-%s', $venta->TidCodi, $venta->VtaSeri, $venta->VtaNumee);
      $venta->VtaPDF = 1;
      $venta->fe_rptaa = 2;
      $venta->VtaMail = 0;
    });

    static::created(function (Venta $venta) {
    });
  }

  public function inventaryHandler()
  {
    return $this;
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'EmpCodi', 'empcodi');
  }

  public function checkInventary()
  {
    $reduce_inventario = true;
    $items_venta = $this->items;
    if ($pedido = $this->pedido()) {
      $pedido->setCerradoState();
      if ($pedido->isNotaVenta()) {
        $items_pedido = $pedido->items;
        foreach ($items_venta as $item) {
          $productos = $items_pedido->where('DetCodi', $item->DetCodi);
          if ($productos->count()) {
            $cant_pedido = $productos->sum('DetCant');
            if ($item->DetCant > $cant_pedido) {
              $dif =  $item->DetCant - $cant_pedido;
              $item->producto->reduceInventary($dif);
            }
          } else {
            $item->producto->reduceInventary($item->DetCant);
          }
        }
        foreach ($items_pedido as $item) {
          $item->producto->reduceReserved($item->DetCant);
        }
      }
    } else {
      foreach ($this->items as $item) {
        $item->producto->reduceInventary($item->DetCant);
      }
    }
  }

  public function noSend()
  {
    $this->fe_firma = null;
    $this->fe_rpta = "9";
    $this->VtaCDR = "0";
    $this->fe_obse = "";
    $this->save();
  }

  public function amazon()
  {
    return $this->hasOne(VentaAmazon::class, 'VtaOper', 'VtaOper');
  }

  public function caja()
  {
    return $this->hasOne(Caja::class, 'CajNume', 'CajNume')
      ->where('EmpCodi', $this->EmpCodi);
  }

  public function numero()
  {
    return $this->VtaSeri . '-' . $this->VtaNumee;
  }

  public function caja_detalle()
  {
    return $this->hasMany(CajaDetalle::class, 'DocNume', 'VtaOper')
      ->where('CajNume', $this->CajNume);
  }

  public function tipo_doc()
  {
    return $this->TidCodi;
  }

  /**
   * Documento del resumen de anulacion
   * 
   */
  public function anulacion()
  {

    return $this
      ->belongsTo(ResumenDetalle::class, 'VtaNumee', "detNume")
      ->where('EmpCodi', $this->EmpCodi)
      ->where('detseri', $this->VtaSeri)
      ->where('tidcodi', $this->TidCodi);
  }

  public function nube()
  {
    return $this->hasOne(VentaNube::class, 'VtaOper', 'VtaOper');
  }

  public function consulta()
  {
    return $this->hasOne(VentaConsultaSunat::class, 'VtaOper', 'VtaOper');
  }

  public function mails()
  {
    return $this->hasMany(VentaMail::class, 'VtaOper', 'VtaOper');
  }

  public function cliente()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi')->where('TipCodi', 'C')->withoutGlobalScope('noEliminados');
  }

  public function cliente_with()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi')->withoutGlobalScope('noEliminados');
  }

  public function cliente_w()
  {
    return $this->belongsToMany(ClienteProveedor::class, 'PCCodi', 'PCCodi')->withoutGlobalScope('noEliminados');
  }

  public function items()
  {
    return $this->hasMany(VentaItem::class, 'VtaOper', 'VtaOper');
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'MonCodi', 'moncodi');
  }

  public function vendedor()
  {
    return $this->belongsTo(Vendedor::class, 'Vencodi', 'Vencodi')->withoutGlobalScopes();
  }

  public function forma_pago()
  {
    return $this->belongsTo(FormaPago::class, 'ConCodi', 'conCodi');
  }

  public function medio_pago()
  {
    return $this->belongsTo(TipoPago::class, 'TpgCodi', 'TpgCodi');
  }

  public function pagos()
  {
    return $this->hasMany(VentaPago::class, 'VtaOper', 'VtaOper')
      ->where('EmpCodi', $this->EmpCodi);
  }

  public function tipo_nota()
  {
    return $this->belongsTo(TipoNotaCredito::class, 'vtaadoc', 'id');
  }

  /**
   * Detalle del resumen
   * 
   * @return 
   */

  public function resumenDetalle()
  {
    return ResumenDetalle::where('detNume', $this->VtaNumee)
      ->where('detseri', $this->VtaSeri)
      ->where('tidcodi', $this->TidCodi)
      ->where('EmpCodi', $this->EmpCodi)
      ->first();
  }

  /**
   * Si una boleta se encuentra en un resumen de anulaciòn
   * 
   * return bool
   */
  public function enResumenDeAnulacion()
  {
    return (bool)  ResumenDetalle::where('detNume', $this->VtaNumee)
      ->where('detseri', $this->VtaSeri)
      ->where('tidcodi', $this->TidCodi)
      ->where('DetMotivo', Resumen::ANULACION)
      ->first();
  }

  public function detalle_anulacion()
  {
    return ResumenDetalle::where('detNume', $this->VtaNumee)
      ->where('detseri', $this->VtaSeri)
      ->where('tidcodi', $this->TidCodi)
      ->where('DetMotivo', Resumen::ANULACION)
      ->first();
  }

  public function por_enviar($id_resumen = null, $docnume = null, $anulacion = false)
  {
    if (is_null($id_resumen)) {
      // Si no se le pasa un resumen, se busca se busca que no este en otro resumen
      $res = ResumenDetalle::where('detNume', $this->VtaNumee)
        ->where('detseri', $this->VtaSeri)
        ->where('tidcodi', $this->TidCodi)
        ->where('EmpCodi', $this->EmpCodi);
      // ->first();
    } else {
      $res = ResumenDetalle::where('detNume', $this->VtaNumee)
        ->where('tidcodi', $this->TidCodi)
        ->where('detseri', $this->VtaSeri)
        ->where('EmpCodi', empcodi())
        ->where('docNume', '!=', $docnume);
      // ->first();
    }

    if ($anulacion) {
      $res->where('DetMotivo', "A");
    }

    return $res->first();
  }

  /**
   * Si el documento se encuentra en un resumen de anulación
   * 
   * @return false|ResumenDetalle
   */
  public function por_enviarEnAnulacion($id_resumen = null, $docnume = null)
  {
    return $this->por_enviar($id_resumen, $docnume, true);
  }

  public function guia()
  {
    return $this->belongsTo(
      GuiaSalida::class,
      'GuiOper',
      'GuiOper'
    )
      ->where('EmpCodi', $this->EmpCodi);
  }

  public function docRefIsBoleta()
  {
    return $this->VtaTDR == self::BOLETA;
  }

  public function docReferencia()
  {
    return self::where('EmpCodi', $this->EmpCodi)
      ->where('TidCodi', $this->VtaTDR)
      ->where('VtaSeri', $this->VtaSeriR)
      ->where('VtaNumee', $this->VtaNumeR)
      ->first();
  }

  public function guias()
  {
    return GuiaSalida::where([
      ['docrefe', $this->VtaNume],
      ['EmpCodi', $this->EmpCodi],
      ['TidCodi', $this->TidCodi]
    ])
      ->get();
  }

  public function guias_relacionadas()
  {
    return $this->hasMany(GuiaSalida::class, 'vtaoper', 'VtaOper');
  }


  public function isFullSend()
  {
    return $this->items->sum('DetSdCa') == 0;
  }


  public function tipo_documento()
  {
    return $this->belongsTo(TipoDocumentoPago::class, 'TidCodi', 'TidCodi');
  }

  public function cifra_letra()
  {
    return numero_a_letras($this->VtaImpo, 2, $this->MonCodi);
  }

  public function moneda_abbre()
  {
    return $this->moneda->monabre;
  }

  public function codigoReferencia()
  {
    return $this->VtaSeriR . '-' . $this->VtaNumeR;
  }

  public function isContado()
  {
    return $this->ConCodi == "01";
  }

  public static function agregate_cero($numero, $set = 0, $tipo = "VtaOper")
  {
    $numero = $numero ? $numero->{$tipo} : self::ID_INIT;
    $cero_agregar = [null, "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);

    return $codigoLen < 6 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public function defaultEmptySend()
  {
    $this->update(["VtaSdCa" => $this->Vtacant]);
    foreach ($this->items as $item) {
      $item->DetSdCa = $item->DetCant;
      $item->save();
    }
  }


  public static function getNotaVentaVtaOper()
  {
    $nvLast = Venta::OrderByDesc("VtaOper")
      ->where('EmpCodi', empcodi())
      ->where('TidCodi', TipoDocumentoPago::NOTA_VENTA)
      ->first();
    $vtaOper = 1;

    if( $nvLast ){
      $vtaOper = ((int) substr($nvLast->VtaOper,1)) + 1;
    }

    return  'N' . math()->addCero( $vtaOper, 6);;
  }

  public static function findByNume($nume)
  {
    return self::where('VtaNume', $nume)->where('EmpCodi', empcodi())->first();
  }

  public static function UltimoId($tipo_documento = null)
  {
    if ($tipo_documento == TipoDocumentoPago::NOTA_VENTA) {
      return self::getNotaVentaVtaOper();
    } else {
      $ultima_venta = Venta::OrderByDesc("VtaOper")
        ->where('EmpCodi', empcodi())
        ->where('TidCodi', '!=', '52')
        ->first();
      return Venta::agregate_cero($ultima_venta, 1);
    }
  }

  public static function UltimoCorrelativo($serie, $tipo)
  {
    $ultimo_correlativo =
      Venta::OrderByDesc("VtaNumee")->where('VtaSeri', $serie)->where('TidCodi', $tipo)->where('EmpCodi', empcodi())->first();

    return self::agregate_cero($ultimo_correlativo, 1, 'VtaNumee');
  }

  public static function createFactura($request, $con_productos_enviados = false, $totales = null, $save = true)
  {
    $user = user_();
    $porcentajeGlobal = 0;
    $hasDetraccion = $request->hasDetraccion == "true";
    $hasAnticipo = $request->hasAnticipo == "true";
    $tipo_cargo_global = 0;

    # Anticipo
    $anticipoTotal = 0;
    $anticipoCorrelativo = null;
    $anticipoTipoDocumento = null;

    if ($hasAnticipo) {
      $anticipoTotal = $request->anticipoValue;
      $anticipoCorrelativo = $request->anticipoModel->VtaNume;
      $anticipoTipoDocumento = $request->anticipoModel->TidCodi;
    }

    if ($request->descuento_global) {
      $porcentajeGlobal = $request->descuento_global;
      $tipo_cargo_global = self::CARGO_DESCUENTO;
    } else {
      if ($totales->percepcion_porc != "0") {
        $tipo_cargo_global = self::CARGO_PERCEPCION;
        $porcentajeGlobal = $totales->percepcion_porc;
      } else if ($totales->retencion_porc != "0") {
        $tipo_cargo_global = self::CARGO_RETENCION;
        $porcentajeGlobal =  $totales->retencion_porc;
      }
    }

    $loccodi =  $user->localCurrent()->loccodi;
    $empresa = get_empresa();
    $empcodi = $empresa->empcodi;
    $fechaParts = explode("-", $request->fecha_emision);
    $year =  $fechaParts[0];
    $mes = $fechaParts[1];
    $mescodi =  $year . $mes;


    set_timezone();
    $venta = new Venta;
    $venta->VtaOper = $vtaOper = $save ? self::UltimoId($request->tipo_documento) : null;
    $venta->EmpCodi = $empcodi;

    // Guardar totales
    $venta->CuenCodi = $totales;
    $venta->PanAno  = $year;
    $venta->PanPeri = $mes;

    $venta->TidCodi = $request->tipo_documento;
    $venta->VtaSeri = $request->serie_documento;

    if ($save) {
      $correlativo = SerieDocumento::lastNume($request->serie_documento, $loccodi, $empcodi, $request->tipo_documento);
      $nume = $request->serie_documento . "-" . $correlativo;
    } else {
      $correlativo = null;
      $nume = 'VISTA-PRELIMINAR';
    }

    $venta->VtaNumee = $correlativo;
    $venta->VtaNume = $nume;
    $venta->VtaFvta = $request->fecha_emision;
    $venta->VtaFVen = $request->fecha_vencimiento;
    $venta->Vtacant = $totales->total_cantidad;
    $venta->PCCodi  = $request->cliente_documento;
    $venta->ConCodi = $request->forma_pago;
    $venta->TpgCodi = $request->medio_pago;
    $venta->ZonCodi = $request->input('ZonCodi', Zona::DEFAULT_ZONA);
    $venta->MonCodi = $request->moneda;
    $venta->Vencodi = $request->vendedor;
    $venta->DocRefe = $request->doc_ref;
    $venta->VtaTcam = $request->tipo_cambio;
    $venta->VtaObse = $request->observacion;
    $venta->VtaPPer = $porcentajeGlobal;
    $venta->VtaDcto = $totales->descuento_total;
    $venta->VtaISC  = $totales->isc;
    $venta->VtaEsta = "V";
    $venta->UsuCodi = $user->usucodi;
    $venta->MesCodi = $mescodi;
    $venta->LocCodi = $loccodi;
    $venta->VtaPago = 0;
    $venta->VtaEsPe = "NP";
    $venta->VtaSPer = $tipo_cargo_global;
    $venta->VtaAPer = $totales->total_base_percepcion;

    // Detracción
    if ($hasDetraccion) {
      $venta->VtaDetrPorc = $totales->detraccion_porc;
      $venta->VtaDetrCode = $request->detraccionItem;
      $venta->VtaDetrTota = $totales->detraccion;
    }

    # Anticipo
    $venta->VtaTotalAnticipo = $anticipoTotal;
    $venta->VtaNumeAnticipo = $anticipoCorrelativo;
    $venta->VtaTidCodiAnticipo = $anticipoTipoDocumento;
    $venta->TipCodi = 111201;
    $venta->AlmEsta = "SA";
    $venta->VtaSdCa = $con_productos_enviados ? 0 : $totales->total_cantidad;
    $venta->VtaHora = date('H:i:s');
    $venta->vtafact = 0;
    $venta->vtaadoc = null;
    $venta->VtaPedi = $request->nro_pedido;
    $venta->VtaEOpe;
    $venta->User_Crea  = $user->usulogi;
    $venta->CajNume  = $save ? $user->caja_aperturada_new(true,  $loccodi) : null;
    $venta->VtaIGVV = $totales->igv;
    $venta->Vtabase = $totales->total_gravadas;
    $venta->VtaSald = $totales->total_cobrado;
    $venta->VtaImpo = $totales->total_cobrado;
    $venta->VtaTota = $totales->total_cobrado;
    $venta->VtaInaf = $totales->total_inafecta;
    $venta->VtaExon = $totales->total_exonerada;
    $venta->VtaGrat = $totales->total_gratuita;
    $venta->icbper = $totales->icbper;
    $venta->VtaISC = $totales->isc;
    $venta->VtaPerc = $totales->percepcion;
    // nro_pedido
    $venta->User_ECrea = gethostname();
    $venta->fe_fxml;
    $venta->fe_envio;
    $venta->fe_estado;
    $venta->fe_obse;
    $venta->fe_rpta  = 9;
    $venta->fe_rptaa = 2;
    $venta->fe_firma;
    $venta->User_FCrea = date('Y-m-d H:i:s');

    if ($request->tipo_documento == self::NOTA_CREDITO || $request->tipo_documento == self::NOTA_DEBITO) {
      $venta->VtaSeriR = strtoupper($request->ref_serie);
      $venta->VtaTDR   = $request->ref_documento;
      $venta->VtaNumeR = $request->ref_numero;
      $venta->VtaFVtaR = $request->ref_fecha;
      $venta->VtaObse  = TipoNotaCredito::find($request->ref_tipo)->descripcion;
      $venta->vtaadoc  = $request->ref_tipo;
    }

    $venta->VtaXML = 0;
    $venta->VtaPDF = 1;
    $venta->VtaCDR = 0;
    $venta->VtaMail = 0;
    $venta->VtaFMail =  TipoDocumentoPago::isTipoVentas($request->tipo_documento) ? StatusCode::CODE_ERROR_0011 : StatusCode::CODE_EXITO_0001;
    $venta->Numoper;
    $venta->contingencia = (int) SerieDocumento::isContingencia($request->tipo_documento, $request->serie_documento);
    $venta->GuiOper = "";
    $venta->TipoOper = "N";
    if ($save) {
      $venta->save();
    }

    if ($request->nro_pedido) {
      if ($save) {
        optional(Cotizacion::findByNume($request->nro_pedido))->setFacturado($vtaOper);
      }
    }

    return $venta;
  }

  public function registerSerial()
  {
    $serie_d = new SerieDocumento;
    $serie_d->empcodi = session()->get('empresa');
    $serie_d->usucodi = auth()->user()->usucodi;
    $serie_d->tidcodi = $this->TidCodi;
    $serie_d->sercodi = $this->VtaSeri;
    $serie_d->numcodi = $this->VtaNumee;
    $serie_d->defecto = 1;
    $serie_d->loccodi = "001";
    $serie_d->estado = 1;
    $serie_d->save();
    return $serie_d;
  }

  public function isNota()
  {
    return $this->isNotaCredito() || $this->isNotaDebito();
  }


  public function noNeedPay()
  {
    $items = $this->items;
    return $items->count() == $items->where('DetBase', 'GRATUITA')->count();
  }

  public function needPago()
  {
    if (
      $this->isNota() ||
      $this->isProforma()  ||
      !$this->isContado()
    ) {
      return false;
    }

    if (get_option('OpcConta') == 0) {
      return false;
    }

    return true;
  }

  public function isFactura()
  {
    return $this->TidCodi == "01";
  }

  public function sePuedeDarDeBaja()
  {
    $tipo_documento = $this->TidCodi;
    return ($tipo_documento === "01" || $tipo_documento === "07" || $tipo_documento === "08");
  }

  public function isBoleta()
  {
    return $this->TidCodi == "03";
  }

  public function needFactura()
  {
    $empresa = $this->empresa;
    $r = '';

    if ($this->isContingencia() || $this->isNotaVenta() || $this->isProforma()) {
      return false;
    }

    switch ($this->TidCodi) {
      case '01':
        $r = $empresa->fe_envfact;
        break;
      case '03':
        $r = $empresa->fe_envbole && $empresa->isXml_2_1() == "2.1";
        break;
      case '07':
        $r = $empresa->fe_envncre;
        break;
      case '08':
        $r = $empresa->fe_envndebi;
        break;
    }

    return (bool) $r;
  }

  public function pedido()
  {
    return Cotizacion::findByNume($this->VtaPedi, $this->EmpCodi);
  }


  public function deudaSaldada()
  {
    return $this->VtaSald <= 0;
  }


  /**
   * Comprobar si el monto suministrado sobrepasa el de la deuda
   * 
   * @return bool
   */
  public function deudaMenor($valor, $moneda_id = '01', $tCambio = null)
  {
    $tCambio = $tCambio ?? $this->VtaTcam;

    if ($moneda_id != $this->MonCodi) {
      $valor = $moneda_id == Moneda::SOL_ID ? ($valor / $tCambio) : ($valor * $tCambio);
    }

    return $valor > $this->VtaSald;
  }




  /**
   * Actualizar cuando se ha pagado de la deuda, recorriendo los pagos que se han realizado
   * 
   * @return $this
   */
  public function updatedDeuda()
  {
    $totalPagado = 0;

    if ($this->pagos->count()) {
      foreach ($this->pagos as $pago) {
        $totalPagado += $pago->getRealImporte();
      }
    }

    $saldo = $this->importe - $totalPagado;

    $this->update([
      'VtaSald' => decimal($saldo),
      'VtaPago' => decimal($totalPagado)
    ]);

    return $this;
  }

  /**
   * Obtener los pagos realizados por nota de credito del pago de otro documento
   * 
   * @return void
   */
  public function pagos_credito()
  {
    return $this->hasMany(VentaPago::class, 'CtoOper', 'VtaOper');
  }

  /**
   * Actualizar la deuda de la nota de credito
   * 
   * @return $this
   */
  public function updateDeudaByPagoNotaCredito()
  {
    $this->updatedDeuda();

    $totalPagado = $this->VtaPago;

    $pagos = $this->pagos_credito;

    if ($pagos->count()) {

      foreach ($pagos as $pago) {
        $totalPagado += $pago->getRealImporte();
      }

      $saldo = $this->importe - $totalPagado;

      $this->update([
        'VtaSald' => decimal($saldo),
        'VtaPago' => decimal($totalPagado)
      ]);
    }
  }

  public function updateEnvio()
  {
    $guias = $this->guias();

    if ($guias->count()) {
      $this->VtaSdCa = $this->Vtacant - $guias->sum('guicant');
      foreach ($this->items as $item) {
        $guiaItems = $item->guiasItems();
        $item->DetSdCa = $item->DetCant - $guiaItems->sum('Detcant');
        $item->save();
      }
    } else {
      $this->resetProductosPorEnviados();
    }

    $this->save();
  }


  public function resetProductosPorEnviados()
  {

    $this->update(['VtaSdCa' => $this->Vtacant]);

    foreach ($this->items as $item) {
      $item->update(["DetSdCa" => $item->DetCant]);
    }
  }



  public function dec($value, $decimal = 2)
  {
    return number_format((float)$value, $decimal, '.', '');
  }

  public static function dec_s($value, $decimal = 2)
  {
    return number_format((float)$value, $decimal, '.', '');
  }

  public function isNotaCredito()
  {
    return $this->TidCodi == self::NOTA_CREDITO;
  }

  public function isNotaDebito()
  {
    return $this->TidCodi == self::NOTA_DEBITO;
  }

  public function sinigv()
  {
  }

  public function nameFile($ext = '')
  {
    return $this->empresa->EmpLin1 . '-' . $this->TidCodi . '-' . $this->numero() . $ext;
  }

  public function nameCdr($ext = ".xml")
  {
    return 'R-' . $this->nameFile($ext);
  }

  public function guias_ventas()
  {
    return $this->hasMany(GuiaVenta::class, 'VtaOper', 'VtaOper');
  }

  public function getNumerosGuias()
  {
    $guias_numeros = [];

    if ($this->hasGuiaReferencia()) {
      $guias_numeros[] =  $this->getNameGuiaCorrelativeNew();
    } else {
      $guias_venta = $this->guias_ventas->load('guia');
      foreach ($guias_venta as $guia_venta) {
        $guias_numeros[] =  $guia_venta->guia->getNumberCorrelative(false);
      }
    }

    return $guias_numeros;
  }


  public function modifyData(array $data)
  {

  }

  public function dataPdf($formato = Venta::FORMATO_A4, $get_url_logo_ticket = false, $items = null)
  {    
    $venta = $this->toArray();
    $isNotaVenta = $this->isNotaVenta();
    $firma = $isNotaVenta ? null : $this->dataQR();
    $cliente = $this->cliente;
    $items_fake = $items !== null;
    $items = $items ?? $this->items;
    $this->refresh();

    $e = $this->empresa;
    $opciones = $e->opcion;
    if( $items_fake ){
      $placa = $items->first()->getPlaca();
    }
    else {
      $placa = $this->hasPlaca() ? $this->getPlaca() : false;
    }

    $logo_ticket_url = $get_url_logo_ticket ? $e->getUrlLogoTicket() : null;
    $empresa =  $e->toArray();
    $local = Local::find($this->LocCodi);
    $empresa['igv_porc'] = $e->opcion->Logigv;
    $logoMarcaAgua = null;
    $logoSubtitulo = null;
    $bancos = Venta::getFormatBancos($e->bancos->load('banco')->groupBy('BanCodi'));
    $telefonos = $e->telefonos();
    $telefonos_local = $local->LocTele;
    $condiciones = explode("-", CondicionVenta::getDefault($this->EmpCodi));
    $logoDocumento  =  $e->getLogo($formato);
    $logoMarcaAguaSizes = null;
    $decimales = $this->isDolar() ? $opciones->DecDola :  $opciones->DecSole;
    $empresa['EmpLogo'] = null;
    $venta['empresa'] = null;
    $venta['nombre_documento'] = $this->nombreDocumento();

    $size = [
      Venta::FORMATO_TICKET => 100,
      Venta::FORMATO_A4 => 150,
      Venta::FORMATO_A5 => 100,
    ][$formato];

    $qr = $isNotaVenta ? false : \QrCode::format('png')->size($size)->generate($firma);
    $qrData = $firma;

    $contacto = "";
    $mostrar_igv = true;

    // dd( $telefonos, $telefonos_local );

    if ($this->isAnulada()) {
      $documento_id = optional($this->anulacion)->docNume;
    } else {
      $documento_id = $this->VtaNume;
    }

    $is_small = $formato == 'a5';

    if ($formato == Venta::FORMATO_A4) {
      $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
      if ($logoMarcaAgua) {
        $logoMarcaAguaSizes = (new ImgStringInfo($e->FE_RESO))->getInfo();
      }
      $logoSubtitulo = $e->getLogoEncodeSubtitulo();
    }

    $orden_campos = $e->getOrdenCampos();
    $guias = $this->getNumerosGuias();
    $data = [
      'title' => $this->nameFile('.pdf'),
      'venta' => $venta,
      'venta2' => $this,
      'venta_rapida' => $e->hasVentaRapida(),
      'rubro' => $e->EmpLin6,
      'empresa' => $empresa,
      'igvPorc' => $opciones->Logigv . '%',
      'isPreventa' => false,
      'nombre_empresa' => false,
      'bancos' => $bancos,
      'logoDocumento'  => $logoDocumento,
      'logoMarcaAgua' => $logoMarcaAgua,
      'logoSubtitulo' => $logoSubtitulo,
      'direccion' => $e->getLocalDireccion($this->LocCodi),
      'cliente_correo' => getNombreCorreo($e->EmpLin3),
      'correo' => $e->EmpLin3,
      'telefonos'     => $telefonos,
      'telefonos_local'     => $telefonos_local,
      'fecha_emision_' => $this->VtaFvta,
      'contacto' => $contacto,
      'vendedor_nombre'     => $this->vendedor->vennomb,
      'mostrar_igv'     => $mostrar_igv,
      'nombre_documento' => $this->nombreDocumento(),
      'documento_id' => $documento_id,
      'condiciones' => $condiciones,
      'condiciones' => $condiciones,
      'observacion' => $this->VtaObse,
      'peso' => $this->getPesoTotal(),
      'base' => $this->Vtabase,
      'igv_porcentaje' => $items->first()->DetIGVV,
      'igv' => $this->VtaIGVV,
      'total' => $this->VtaImpo,
      'guias' => $guias,
      'orden_campos' => $orden_campos,
      'logoMarcaAguaSizes' => $logoMarcaAguaSizes,
      'decimals' => $decimales,
      'cifra_letra' => $this->cifra_letra(),
      'cliente'     => $cliente,
      'moneda'      => $this->moneda,
      'moneda_abreviatura'  => $this->moneda->monabre,
      'forma_pago'  => $this->forma_pago,
      'medio_pago_nombre'  => $this->getMedioPagoNombreForPDF(),
      'items'       => $items,
      'qr'          => $qr,
      'qrData'          => $qrData,
      'formato_small' => $is_small,
      'logo_ticket_url' => $logo_ticket_url,
      'firma'          => $this->fe_firma,
      'peso'          => $this->getPesoTotal(),
      'status_code' => $this->status_code,
      'placa' => $placa,
      'status_message' => $this->getStatusMessage()
    ];

    return $data;
  }

  public function dataQR($empresa_ruc = null, $cliente_tipo_documento = null, $cliente_documento = null)
  {
    $empresa_ruc = $empresa_ruc ?? $this->empresa->EmpLin1;
    $cliente_tipo_documento = $cliente_tipo_documento ?? $this->cliente->TDocCodi;
    $cliente_documento = $cliente_documento ?? $this->cliente->PCRucc;

    return ($empresa_ruc . '|' .
      $this->TidCodi . '|' .
      $this->VtaSeri . '|' .
      $this->VtaNumee . '|' .
      $this->VtaIGVV . '|' .
      $this->VtaImpo . '|' .
      $this->VtaFvta . '|' .
      $cliente_tipo_documento . '|' .
      $cliente_documento
    );
  }

  public function setStatusCdr($path = null)
  {
    return;
    // $venta->fe_rpta = $codigo;    
    // $venta->save();
    // return $sent;
  }

  public function saveXML()
  {
    if ($this->isProforma() || $this->isNotaVenta()) {
      return;
    }

    $input = xmlCreador($this);
    $data = $input->guardar();
    $this->fe_firma = $data['firma'];
    $this->VtaXML = 1;
    $this->save();
  }


  public function savePdf($formato = 'a4', $return_data = false)
  {
    $empresa = $this->empresa;
    $data = $this->dataPdf($formato);
    $viewPDF =  $empresa->getViewPDF();
    $pdf = \PDF::loadView($viewPDF, $data);
    $pdf->setPaper('a4');
    $nameFile = $this->nameFile('.pdf');
    FileHelper($empresa->EmpLin1)->save_pdf($nameFile, $pdf->output());
    $path = file_build_path('temp', $nameFile);
    $pdf->save($path);

    $asset = asset($path);

    if ($return_data) {
      return ['path' => $asset, 'data' => $data];
    }
    return $asset;
  }


  public function generatePDF(
    $formato = PDFPlantilla::FORMATO_A4,
    $save = true,
    $saveTemp = false,
    $impresion_directa = false,
    $generator = PDFGenerator::HTMLGENERATOR,
    $items = null,
    $forceSaveA4 = false
  ) {


    $empresa = $this->empresa;

    $plantilla  = $this->getPlantilla($formato);
    
    // dd($plantilla);
    // exit();
    
    $data = $this->dataPdf($formato, $impresion_directa, $items);
    $tempPath = '';
    $pdf = new PDFGenerator(view($plantilla->vista, $data), $generator);
    $pdf->generator->setGlobalOptions($plantilla->getSetting());

    if( $formato == PDFPlantilla::FORMATO_TICKET ){
      $pdf->generator->updatePageHeight( $data['items']->count(), true);
    }

    $namePDF = $this->nameFile('.pdf');

    if ($save) {
      FileHelper($empresa->EmpLin1)->save_pdf($namePDF, $pdf->generator->toString());
    }

    if ($saveTemp) {
      $tempPath = file_build_path('temp', $namePDF);
      $pdf->save($tempPath);
    }

    if($forceSaveA4){
      $data['logoDocumento'] = $empresa->getLogo(PDFPlantilla::FORMATO_A4);
      $data['logoMarcaAgua'] = $empresa->getLogoEncodeMarcaAgua();
      $data['logoMarcaAguaSizes'] = (new ImgStringInfo($empresa->FE_RESO))->getInfo();
      $data['logoSubtitulo'] = $empresa->getLogoEncodeSubtitulo();
      $plantilla  = $this->getPlantilla(PDFPlantilla::FORMATO_A4);
      $pdf = new PDFGenerator(view($plantilla->vista, $data), $generator);
      $pdf->generator->setGlobalOptions($plantilla->getSetting());
      FileHelper($empresa->EmpLin1)->save_pdf($namePDF, $pdf->generator->toString());
    }
    
    return [
      'data' => $data,
      'tempPath' => $tempPath,
    ];
  }

  /**
   * Guardar pdf y luego imprimir en la ticketera por defecto, 
   * Si se esta mandando a imprimir en la ticketera y esta imprimio correctamnete, devolver falso para indiciar que no hace falta mostrar en vista
   * 
   * @return bool
   */
  public function savePrintPDF($print, $formato)
  {
    $info = $this->savePDF(Venta::FORMATO_A4, true);

    if ($print && $formato == Venta::FORMATO_TICKET) {
      $this->printPDFTicket($info['data']);
    }
  }


  /**
   * Imprimir en ticketera predeterminada si es que existe, devolver booleano en caso de una impresiòn exitosa
   * 
   * @return bool
   */
  public function printPDFTicket(array $data = [])
  {
    if (!$data) {
      $data = $this->dataPdf();
    }
    $printer = new PDFDirect($data['empresa'][Empresa::CAMPO_NOMBRE_IMPRESORA], $data, $data['empresa'][Empresa::CAMPO_NUMERO_COPIAS]);
    return $printer->print();
  }

  public function updateSaldoPagado($value)
  {
    $this->VtaPago -= $value;
    $this->VtaSald += $value;
    $this->save();
  }


  public function hasDoc($doc, $ruc = null)
  {
    $ruc = $ruc ?? $this->empresa->EmpLin1;

    $fileHelper = FileHelper($ruc);
    switch ($doc) {
      case 'pdf':
        if ($this->VtaPDF && $fileHelper->pdfExist($this->nameFile('.pdf'))) {
          return true;
        }
        break;
      case 'xml':
        if ($this->VtaXML && $fileHelper->xmlExist($this->nameFile('.xml'))) {
          return true;
        }
      case 'zip':
        if ($this->VtaXML && $fileHelper->xmlExist($this->nameFile('.zip'))) {
          return true;
        }
        break;
      case 'cdr':
        if ($this->VtaCDR && $fileHelper->cdrExist($this->nameCdr('.zip'))) {
          return true;
        }
        break;
    }
    return false;
  }

  public function getDocPath($doc)
  {
    if ($this->hasDoc($doc)) {
      switch ($doc) {
        case 'pdf':
          return $this->pdfPath();
          break;
        case 'xml':
          return $this->xmlPathNoZip();
          break;
        case 'cdr':
          return $this->cdrPath();
          break;
      }
    }
  }

  public function pdfPath()
  {
    return env('SAINFO_PDF') . $this->nameFile('.pdf');
  }
  public function xmlPath()
  {
    return env('SAINFO_XML') . $this->nameFile('.xml');
  }
  public function xmlZipPath()
  {
    return env('SAINFO_XML') . $this->nameFile('.zip');
  }
  public function xmlPathNoZip()
  {
    return env('SAINFO_DATA') . $this->nameFile('.xml');
  }
  public function cdrPath()
  {
    return env('SAINFO_CDR') . 'R-' . $this->nameFile('.zip');
  }

  public static function set1033error($ids = [])
  {
    if (count($ids)) {
      foreach ($ids as $id) {
        $v = self::find($id);
        $v->save();
      }
    }
  }


  public function isCdrNotSend()
  {
    return $this->fe_rpta == "9";
  }

  public static function getBoletasNoEnviadas($fecha, $id_resumen = null, $docnume = null, $serie = "B001")
  {

    $boletas =
      self::with(['cliente_with' => function ($q) {
        $q->where('TipCodi', 'C');
      }])
      ->where('VtaFvta', $fecha)
      ->where('TidCodi', '03')
      ->where('contingencia',  '=', '0')
      ->where('VtaSeri', $serie)
      ->orderBy('VtaNumee')
      ->get();

    $boletas_no_enviadas = [];

    if (!count($boletas)) {
      return [];
    }

    foreach ($boletas as $boleta) {

      if (is_null($boleta->por_enviar($id_resumen, $docnume))) {

        $boleta_arr = $boleta->toArray();

        $boleta_arr['link'] = route('ventas.show', $boleta_arr['VtaOper']);

        if ($boleta->isAnulada()) {

          $items = $boleta->items;

          $igv_porc = (float) ("1.18" . $items->first()->DetIGVV);
          $importe = $items->where('DetBase', 'GRAVADA')->sum('DetImpo');
          $boleta_arr["Vtabase"] = self::dec_s($importe / $igv_porc);
          $boleta_arr["VtaExon"] = $items->where('DetBase', 'EXONERADA')->sum('DetImpo');
          $boleta_arr["VtaInaf"] = $items->where('DetBase', 'INAFECTA')->sum('DetImpo');
          $boleta_arr["VtaISC"] = 0;
          $boleta_arr["icbper_value"] = $items->sum('icbper');
          $boleta_arr["VtaImpo"] = $items->sum('DetImpo');
          $boleta_arr["VtaIGVV"] = self::dec_s($importe - ($importe / $igv_porc));
        }
        array_push($boletas_no_enviadas, $boleta_arr);
      }
    }

    return $boletas_no_enviadas;
  }

  public function isAnulada()
  {
    return $this->VtaEsta === "A"  || $this->VtaFMail == StatusCode::CODE_0003;
  }

  public function isAceptado()
  {
    return $this->VtaFMail == StatusCode::CODE_0001;
  }


  public function nombreDocumento()
  {
    // 
    if ($this->isAnulada()) {
      return "COMUNICACIÓN DE BAJA";
    }

    switch ($this->TidCodi) {
      case '01':
        return 'FACTURA ELECTRÓNICA';
        break;
      case '03':
        return 'BOLETA ELECTRÓNICA';
        break;
      case '07':
        return 'NOTA DE CREDITO ELETRÓNICA';
        break;
      case '08':
        return 'NOTA DE DEBITO ELETRÓNICA';
        break;
      case '52':
        return 'NOTA DE VENTA';
        break;
      case '50':
        return 'PROFORMA';
        break;
    }
  }

  public function successAnulacion($ticket)
  {
    $this->VtaEsta = "A";
    $this->fe_estado = "Anulado SUNAT (0)";
    $this->fe_obse = $ticket;
    $this->fe_rptaa = 0;
    $this->AlmEsta = "AA";
    $this->VtaTota = 0;
    $this->vtaanu = date('d/m/Y h:m:i a');
    $this->save();

    $this->anularPago();
    $this->cancelarGuia();
  }

  /**
   * Desacer las operaciones de la guia, si que tiene una asociada
   * 
   * @return void
   */
  public function cancelarGuia()
  {
    return optional($this->guia)->cancel();
  }

  public function successEnvio($content)
  {
    $respuesta = extraer_from_content($content, $this->nameCdr(), ["ResponseCode", "Description"]);

    if ($respuesta[0] == 0) {
      $this->successState($respuesta[0],  substr($respuesta[1], 0, 100));
    }
  }

  public function successState($rpta, $obse)
  {
    $this->update([
      'fe_rpta' => $rpta,
      'fe_obse' =>  $obse,
      'VtaFMail' =>  StatusCode::CODE_0001,
      'fe_estado' => "ENVIADO SUNAT(" . $rpta . ")",
      'VtaCDR' => 1,
    ]);
  }


  public function updateDocumento()
  {
    // $this->
  }

  public function deleteComplete($deleteSerie = true)
  {
    // Borrar detalles de esa venta
    foreach ($this->items as $item) {
      $item->delete();
    }

    // Borrar los pagos
    foreach ($this->pagos as $pago) {
      $pago->removeMovimiento();
      $pago->delete();
    }

    // Borrar Guia
    foreach ($this->guias() as $guia) {
      $guia->deleteComplete();
    }

    // Borrar la serie del documento
    if ($deleteSerie) {
      SerieDocumento::deleteDocumento($this->VtaOper);
    }

    $this->deleteFiles();

    // Borrar la cabecera
    $this->delete();
  }


  public function deleteFiles()
  {
    $fh = fileHelper($this->empresa->ruc());
    $pdf = $this->nameFile('.pdf');
    $xml = $this->nameFile('.xml');
    $xml_zip = $this->nameFile('.zip');
    $cdr = $this->nameCdr('.zip');
    $fh->deletePDF($pdf);
    $fh->deleteEnvio($xml);
    $fh->deleteEnvio($xml_zip);
    $fh->deleteData($xml);
    $fh->deleteCDR($cdr);
  }


  public function isUltimoDocumento()
  {
    $serie = SerieDocumento::where('tidcodi', $this->TidCodi)
      ->where('empcodi', $this->EmpCodi)
      ->where('sercodi', $this->VtaSeri)
      ->first();

    return $this->VtaNumee == $serie->numcodi;
  }


  public function borrar_resumen()
  {
    if ($resumen_detalle = $this->por_enviar()) {
      $resumen_detalle->resumen->delete();
      $resumen_detalle->delete();
    }
  }
  public function ejecutar_egreso()
  {
    if ($this->caja_detalle && $this->caja->isCerrada()) {

      $caja_aperturada = User::find("01")->caja_aperturada(false);

      if (!is_null($caja_aperturada)) {
        // @TODO PONER EGRESO
      }
    }
  }

  public function anular_documento()
  {
    $this->VtaEsta = "A";
    $this->save();
  }

  public function setAnulacioMontos()
  {
    $this->update([
      'Vtacant' => 0,
      'Vtabase' => 0,
      'VtaIGVV' => 0,
      'VtaDcto' => 0,
      'VtaInaf' => 0,
      'VtaExon' => 0,
      'VtaGrat' => 0,
      'VtaISC'  => 0,
      'VtaImpo' => 0,
      'VtaPago' => 0,
      'VtaSald' => 0,
      'VtaPPer' => 0,
      'VtaAPer' => 0,
      'VtaPerc' => 0,
      'VtaTota' => 0,
      'VtaSPer' => 0,
      'VtaSDCa' => 0,
    ]);
  }


  public function anular_boleta($sendEstadoAnulado = true)
  {
    $this->update([
      'Vtacant' => 0,
      'Vtabase' => 0,
      'VtaIGVV' => 0,
      'VtaDcto' => 0,
      'VtaInaf' => 0,
      'fe_rpta' => 0,
      'VtaExon' => 0,
      'VtaGrat' => 0,
      'VtaISC'  => 0,
      'VtaImpo' => 0,
      'VtaEsta' => $sendEstadoAnulado ? "A" : 'V',
      'VtaPago' => 0,
      'VtaSald' => 0,
      'VtaPPer' => 0,
      'VtaAPer' => 0,
      'VtaPerc' => 0,
      'VtaTota' => 0,
      'VtaSPer' => 0,
      'VtaSDCa' => 0,
    ]);

    $this->anularPago();

    if ($this->hasGuiaReferencia()) {
      optional($this->guia)->anular();
    }
  }

  public function anular()
  {
    return $this->anular_boleta();
  }

  public function get_importe_by_month($month)
  {
  }

  public static function searchRangoVtaNume($fecha_desde, $fecha_hasta, $TidCodi = "01", $VtaSeri = "F001")
  {
    $ventas = self::whereBetween('VtaFvta', [$fecha_desde, $fecha_hasta])
      ->where('TidCodi', $TidCodi)
      ->where('VtaSeri', $VtaSeri)
      ->where('EmpCodi', get_empresa('empcodi'))
      ->get();

    $rango = [
      'desde' => "000000",
      'hasta' => "000000",
    ];

    if ($ventas->count()) {
      $rango['desde'] = $ventas->min('VtaNumee');
      $rango['hasta'] = $ventas->max('VtaNumee');
    }

    return $rango;
  }

  public function xmlEnvioPath()
  {
    return env('SAINFO_ENVIO') . $this->nameFile('.zip');
  }

  public function resumenAmazonSave($boleta)
  {
    if ($resumen_detalle = $boleta->por_enviar()) {
      $resumen = $resumen_detalle->resumen;
      // $resumen->items;
    }

    return false;
  }


  public function saveAmazon($reenviar = false)
  {

    $enviar = [];

    function agregar_documentos($path, $name, &$arr, &$file_exist_var)
    {
      $file_exist_var = file_exists($path);

      if ($file_exist_var) {
        $arr[] = [
          'path_origen' => $path,
          'path' => ('documentos/' . get_empresa('EmpLin1') . '/' . $name),
          'content' => file_get_contents($path),
        ];
      }
    }

    $cdr = 0;
    $xml = 0;
    $pdf = 0;

    $cdrPath = $this->cdrPath();
    $xmlPath = $this->xmlEnvioPath();
    $nameCdrFile = $this->nameFile('.zip');
    $nameXmlFile = $this->nameFile('.zip');

    if ($this->isBoleta()) {
      if ($resumen_detalle = $this->por_enviar()) {
        $cdrPath = $resumen_detalle->resumen->cdrPath();
        $xmlPath = env('SAINFO_ENVIO') . $resumen_detalle->resumen->nameFile(false, '.zip');
        $nameCdrFile = $resumen_detalle->resumen->nameFile(false);
        $nameXmlFile = $resumen_detalle->resumen->nameFile(false);
      }
    }

    // Agregar a los documentos a enviar
    if ($reenviar == false && !($this->isNubeSave('XML') && $this->isNubeSave('PDF') && $this->isNubeSave('CDR'))) {
      $cdr = $this->isNubeSave('CDR');
      $xml = $this->isNubeSave('XML');
      $pdf = $this->isNubeSave('PDF');

      if (!$cdr) agregar_documentos($cdrPath, 'R-' . $nameCdrFile, $enviar, $cdr);
      if (!$pdf) agregar_documentos($this->pdfPath(), $this->nameFile('.pdf'), $enviar, $pdf);
      if (!$xml) agregar_documentos($xmlPath, $nameXmlFile, $enviar, $xml);
    } else if ($reenviar) {
      agregar_documentos($cdrPath, 'R-' . $nameCdrFile, $enviar, $cdr);
      agregar_documentos($this->pdfPath(), $this->nameFile('.pdf'), $enviar, $pdf);
      agregar_documentos($xmlPath, $nameXmlFile, $enviar, $xml);
    }

    if (count($enviar)) {
      foreach ($enviar as $archivo) {
        Storage::disk('s3')->put($archivo['path'], $archivo['content']);
      }
      return $this->guardado_nube($xml, $pdf, $cdr);
    }
  }


  public function isNubeSave($file)
  {
    if (is_null($this->nube)) {
      return false;
    } else {
      return $this->nube->{$file};
    }
  }


  public function guardado_nube($xml, $pdf, $cdr)
  {
    if (is_null($this->nube)) {
      VentaNube::create([
        'VtaOper'   => $this->VtaOper,
        'XML'       => (int) $xml,
        'PDF'       => (int) $pdf,
        'CDR'       => (int) $cdr,
        'User_Crea' => auth()->user()->usulogi,
      ]);
    } else {
      $this->nube->update([
        'XML'       => (int) $xml,
        'PDF'       => (int) $pdf,
        'CDR'       => (int) $cdr,
      ]);
    }
  }

  public static function getPendientes()
  {
  }

  public function isNotSaveInAmazon()
  {
    return !is_null($this->amazon);
  }


  public function fileExist($type)
  {
    $fileHelper = new FHelper($this->empresa->EmpLin1, $this->empresa->codigo);
    $r = ['success'  => false, 'nameFile' => ''];

    switch ($type) {
      case FHelper::ENVIO:
        $r['nameFile'] = $this->nameFile('.zip');
        break;
      case FHelper::CDR:
        $r['nameFile'] = $this->nameCdr('.zip');
        break;
      case FHelper::PDF:
        $r['nameFile'] = $this->nameFile('.pdf');
        break;
    }


    if ($this->isBoleta()) {
      if ($fileHelper->existsInLocal($type, $r['nameFile'])) {
        $r['success'] = true;
      } else {
        $resumen_detalle = $this->anulacion;
        if ($resumen_detalle) {
          $resumen = $resumen_detalle->resumen;

          switch ($type) {
            case FHelper::ENVIO:
              $r['nameFile'] = $resumen->nameFile(false, '.zip');
              break;
            case FHelper::CDR:
              $r['nameFile'] = $resumen->nameFile(true, '.zip');
              break;
            case FHelper::PDF:
              $r['nameFile'] = $r['nameFile'];
              break;
          }
          $r['success'] = $fileHelper->existsInLocal($type, $r['nameFile']);
        }
      }
    } else {
      $r['success'] = $fileHelper->existsInLocal($type, $r['nameFile']);
    }

    return $r;
  }

  /**
   * Anular pago de una venta
   * 
   * @return void
   */

  public function anularPago()
  {
    foreach ($this->pagos as $pago) {
      $pago->anularPayment();
      $pago->updateMovimiento();
      $pago->delete();
    }
  }

  public function apply($callback)
  {
    $success = true;
    $result = null;
    $message = '';

    DB::beginTransaction();
    try {
      $result = $callback();
      DB::commit();
    } catch (\Exception $e) {
      $success = false;
      $message = $e->getMessage();
      DB::rollback();
    }

    return [
      'success' => $success,
      'result' => $result,
      'message' => $message
    ];
  }

  public static function anulacionMasiv(array $ids)
  {
    $success = false;
    $message = "";

    // $result = TransactionHelper::apply()

    DB::beginTransaction();

    try {
      foreach ($ids as $id) {
        Venta::find($id)->anulacion();
      }
      $success = true;
      $message = "anulacion exitosa";
      DB::commit();
    } catch (\Exception $e) {
      $success = false;
      $message = $e->getMessage();
      DB::rollback();
    }

    return $message;
  }


  public function updateCorrelative()
  {
    return $this->update([
      'VtaNume' => $this->VtaSeri . "-" . $this->VtaNumee
    ]);
  }

  public function setInitialState()
  {
    $this->update([
      'VtaEsta' => 1
    ]);

    $this->calcularTotales();
  }

  public function anulate()
  {
    // DocumentoAnulacion::dispatchNow($this);
  }

  public function anularPorRC()
  {
    if ($this->isBoleta()) {
      return true;
    }

    if ($this->isNotaCredito() && $this->docRefIsBoleta()) {
      return is_ose() ? false : true;
    }

    return false;
  }

  public function createResumenAnulacion()
  {
    $generator = new GenerateResumenAnulacion($this);
    return $generator->generate();
  }

  /**
   * Boton para enlazar a resumen de anulación
   *
   * @return void
   */
  public function btnAnulacion()
  {
    $linkTemplate = "<a class='btn btn-default' href='%s'>%s</a>";
    $href = route('boletas.agregar_boleta', $this->resumenDetalle()->numoper, $this->resumenDetalle()->docNume);
    $text = $this->isAnulada() ? 'Documento Anulado' : 'Documento de Baja';
    $link = sprintf($linkTemplate, $href, $text);
    return $link;
  }

  public function sendSunatPendiente($checkFirstCheckStatus = true, $empresa = null, $checkStatusAfter = true)
  {
    $empresa = $empresa ?? $this->empresa;
    $isOse =  $empresa->is_ose();
    $isProduccion = $empresa->isProduction();
    
    // Si no es ose, y esta en producción
    $checkStatusSunat = !$isOse && $isProduccion;
    
    // Comprobar el status del documento antes de hacer nada
    if ($checkFirstCheckStatus && $checkStatusSunat) {
      $this->searchSunatGetStatus();
    }
    
    if ($this->isPendiente()) {
      $sent = Sunat::sentPendiente($this, $this->EmpCodi);
      
      if($checkStatusAfter){
        $this->searchSunatGetStatus(false);
      }
      return $sent;
    }

    else {
      return ['data' => $this->fe_obse, 'code_http' => 200];
    }
  }

  public function reportData()
  {
    return new VentaReportData();
  }

  public function recalculate()
  {
    $items = $this->items;

    foreach ($items as $item) {
      $item->fillCalculate(true);
    }

    $this->calcularTotales();
  }

  public function scopePendientes($query, $sendBoletas = false)
  {
    $tipos = $sendBoletas ? ["01", "03", "07", "08"] : ["01", "07", "08"];

    return $query
      ->where('VtaFMail', '=', StatusCode::ERROR_0011['code'])
      ->where('TidCodi', $tipos);
  }

  /**
   * Actualizar todos los productos enviados de la venta
   * 
   */
  public function updateProductosEnviados()
  {
    UpdateProductosEnviados::dispatch($this);
  }

  public function registerItemCalculo($item_calculo)
  {
    $this->calculator->registerItemTotal($item_calculo);
  }

  public function calculateTotales()
  {
    if (!$this->calculator->hasData()) {
      $this->calculator->setParameters($this->VtaPPer);
      $this->calculator->calculateTotales();
    }
    return $this->calculator->getTotal();
  }

  public function getTotalDocumentoAttribute()
  {
    return $this->calculator->getTotal();
  }

  public function addToTotalsItems($item)
  {
    $this->totales_items[] = $item;
  }


  public function totalizeFromDatabase()
  {
    $totales = $this->CuenCodi;
    $total_valor_bruto_venta = $totales['total_valor_bruto_venta'];
    $total_valor_venta = $totales['total_valor_venta'];
    $descuento_global = $totales['descuento_global'];
    $retencion = $totales['retencion'] ?? 0;
    $total_base_isc = $totales['total_base_isc'] ?? 0;
    $total_base_percepcion = $totales['total_base_percepcion'] ?? 0;
    $valor_venta_por_item_igv = $totales['valor_venta_por_item_igv'];
    $impuestos_totales = $totales['impuestos_totales'];
    $total_importe = $totales['total_importe'];

    return $this->totales = (object) [

      'total_cantidad' => $this->Vtacant,
      'total_valor_bruto_venta' => $total_valor_bruto_venta,
      'total_valor_venta' => $total_valor_venta,
      //
      'descuento_global'  => $descuento_global,
      //
      'retencion'  => $retencion,
      'descuento_total'   =>  $this->VtaDcto,
      //
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,
      'icbper' => $this->icbper,
      'anticipo' => $this->VtaTotalAnticipo,
      // Percepciòn
      'percepcion_porc' => $this->VtaDetrPorc,
      'percepcion' => $this->VtaPerc,
      'total_base_percepcion' => $total_base_percepcion,

      // Detracciòn
      'detraccion_porc' => $this->VtaDetrPorc,
      'detraccion' => $this->VtaDetrTota,
      'igv' => $this->VtaIGVV,
      'igv_porc' => $totales->igv_porc ?? config('app.parametros.igv_antiguo'),
      'isc' => $this->VtaISC,
      'total_base_isc' => $total_base_isc,
      'impuestos_totales' => $impuestos_totales,
      'total_gravadas' => $this->Vtabase,
      'total_exonerada' => $this->VtaExon,
      'total_inafecta' => $this->VtaInaf,
      'total_gratuita' => $this->VtaGrat,
      'total_importe' => $total_importe,
      'total_cobrado' => $this->VtaImpo
    ];
  }

  /**
   * Devolver la variable con los calculos generados
   * 
   * @return mixed
   */
  public function getTotalesDocumentoAttribute()
  {
    return $this->totales ?? $this->totalizeFromDatabase();
  }

  public function totalizeCalculate()
  {
    return;
  }


  public function getPesoTotal()
  {
    $data = $this->CuenCodi;

    if ($data) {
      return isset($data['total_peso']) ? $data['total_peso'] : 0;
    }

    return 0;
  }

  public function anularDocumento()
  {
    AnularDocumento::dispatchNow($this);
  }

  public function setTotalesCantidad()
  {
    $totales = (object) $this->CuenCodi;

    $esExonerada = (int) $totales->impuestos_totales == 0;

    $exon = $esExonerada ? $totales->total_importe : 0;
    $base = $esExonerada ? 0 : $totales->valor_venta_por_item_igv;
    $grat = 0;
    $inaf = 0;

    $cant = $this->items->sum('DetCant');
    $igv = $totales->impuestos_totales;
    $dcto = $totales->descuento_global;
    $exon = $exon;
    $grat = 0;
    $total = $totales->total_importe;
    $this->Vtacant = $cant;
    $this->Vtabase = $base;
    $this->VtaIGVV = $igv;
    $this->VtaDcto = $dcto;
    $this->VtaExon = $exon;
    $this->VtaGrat = $grat;
    $this->VtaImpo = $total;
    $this->VtaTota = $total;
    $this->VtaPago = $total;
    $this->VtaTota = $total;
    $this->VtaFMail = "0001";
    $this->VtaEsta = "V";
    $this->save();
  }

  public function getFiles()
  {
    return (new GetFiles(get_ruc(),  $this->TidCodi . '-' . $this->VtaNume))->handle();
  }

  public function getIGVPorc()
  {
    // return $this->items->first()->DetIGVV;
    // return $this->items->first()->DetIGVV;
  }
}
