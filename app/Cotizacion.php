<?php

namespace App;

use App\Zona;
use App\Moneda;
use App\Traits\InteractWhatApp;
use Illuminate\Support\Facades\DB;
use App\Jobs\Empresa\ImgStringInfo;
use App\Models\MedioPago\MedioPago;
use App\Jobs\Cotizacion\SetFacturado;
use App\Util\PDFGenerator\PDFGenerator;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Cotizacion\Traits\handleStates;

class Cotizacion extends Model
{
  use
    InteractWhatApp,
    ModelEmpresaScope,
    UsesTenantConnection,
    handleStates;

  public $table = 'cotizaciones';
  public $primaryKey = "CotNume";
  public $keyType = "string";
  public $incrementing = false;
  public $timestamps = false;

  const ID_INIT = "P001-000001";
  const PREFIX_COTI = 'P001';
  const PREFIX_PREVENTA = 'C001';
  const PREFIX_NOTAVENTA = 'OP';
  const PREFIX_ORDENCOMPRA = 'OC01';

  const COTIZACION = "50";
  const PREVENTA = "53";
  const NOTACOMPRA = "98";
  const ORDEN_COMPRA = '99';
  const STATE_FACTURADO = "F";
  const STATE_PENDIENTE = "P";
  const STATE_ANULADO = "A";
  const STATE_CERRADA = "C";
  const EMPRESA_CAMPO = "EmpCodi";

  const STATES_NAMES = [
    self::STATE_PENDIENTE => "pendiente",
    self::STATE_FACTURADO => "facturado",
    self::STATE_ANULADO => "anulado",
  ];

  public function isPreventa()
  {
    return $this->isNotaVenta();
  }

  public function getMoneda()
  {
    return $this->moncodi;
  }

  public static function ordenCompra($value)
  {
    return 
    self::where('TidCodi1', self::ORDEN_COMPRA)
    ->where('CotNume', $value )
    ->first();
  }



  public static function findByNume($nume)
  {
    return self::find($nume);
  }

  public static function findByUni($uni)
  {
    return self::where('CotUni', $uni)->first();
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'EmpCodi', 'empcodi');
  }

  public function tipo_documento()
  {
    return $this->belongsTo(TipoDocumentoPago::class, 'TidCodi', 'TidCodi');
  }

  public function setCotimpoAttribute($value)
  {
    $this->attributes['cotimpo'] = self::dec($value);
  }

  public function vendedor()
  {
    return $this->belongsTo(Vendedor::class, 'vencodi', 'Vencodi')->withoutGlobalScopes();
  }

  public function venta()
  {
    return $this->belongsTo(Venta::class, 'VtaOper', 'VtaOper');
  }

  public function compra()
  {
    return $this->belongsTo(Compra::class, 'VtaOper', 'CpaOper');
  }

  public function vendedor_()
  {
    return Vendedor::find($this->vencodi);
  }

  public function items()
  {
    return $this->hasMany(CotizacionItem::class, 'CotNume', 'CotNume');
  }

  public function savePdf()
  {
    $path = public_path() . '/temp/' . $this->CotNume . '.pdf';
    $pdf = \PDF::loadView('cotizaciones.pdf', $this->dataPdf());
    $pdf->save($path);
    return $path;
  }

  public function forma_pago()
  {
    return $this->belongsTo(FormaPago::class, 'ConCodi', 'conCodi');
  }

  public function cliente()
  {
    // 
    $tipo = $this->isOrdenCompra() ? ClienteProveedor::TIPO_PROVEEDOR : ClienteProveedor::TIPO_CLIENTE;

    return $this
    ->belongsTo(ClienteProveedor::class, 'PcCodi', 'PCCodi')
    ->where('TipCodi', $tipo );
  }

  public function cliente_with()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PcCodi', 'PCCodi');
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'moncodi', 'moncodi');
  }

  public function usuario()
  {
    return $this->belongsTo(User::class, 'usucodi', 'usucodi');
  }

  public static function agregate_cero($numero)
  {
    $cero_agregar = [null, "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + 1;
    $codigoLen = strlen((string) $codigoNum);

    return $codigoLen < 6 ? ($cero_agregar[$codigoLen] . $codigoNum) : $codigoNum;
  }

  public static function dec($value, $decimal = 2)
  {
    return number_format((float)$value, $decimal, '.', '');
  }

  public static function UltimoId($tidcodi1 = self::COTIZACION)
  {
    $is_coti = $tidcodi1 == self::COTIZACION;
    // $prefix = Cotizacion::getPrefix() $is_coti ? self::PREFIX_COTI : self::PREFIX_PREVENTA;
    $prefix = Cotizacion::getPrefix($tidcodi1);
    $number = self::OrderByDesc('CotNume')
      ->where('TidCodi1', $tidcodi1)
      ->first();

    if (is_null($number)) {
      return $prefix . "-000001";
    } else {
      $lastCotNume = explode("-",  $number->CotNume)[1];
      return $prefix . '-' . self::agregate_cero($lastCotNume);
    }
  }

  public static function getCorrelativo($tipo, $empcodi, $local)
  {
    $serie = SerieDocumento::findSerie(
      $empcodi,
      null,
      $tipo,
      $local
    )->first();


    $numero_siguiente = $serie->numcodi + 1;
    return $serie->sercodi . '-' .  math()->addCero($numero_siguiente, 6);
  }


  public function isCotizacion()
  {
    return $this->TidCodi1 == self::COTIZACION;
  }

  public function isNotaVenta()
  {
    return $this->TidCodi1 == self::PREVENTA;
  }

  public static function guardarFromVenta($data,  $totales)
  {
    $data['tipo'] = $data['tipo_documento'];
    $data["doc_ref"] = $data['nro_pedido'];
    $data["contacto"] = null;


    $series = SerieDocumento::findSerie(
      empcodi(),
      $data['serie_documento'],
      $data['tipo']
    );

    return self::guardar($data, false, $totales, $series);
  }


  public static function guardar($data, $id = false, $totales, $series)
  {
    $c = $id ? Cotizacion::find($id) : new self;

    if (!$id) {
      $c->EmpCodi = empcodi();;
      $local = auth()->user()->localCurrent()->loccodi;
      $c->LocCodi = $local;
      $c->CotNume = $series->first()->getCorrelativeNextNumber('-');
      $c->CotUni =  $data['tipo'] . '-' .  $c->CotNume;
      $date = get_date_info($data['fecha_emision']);
      $c->usucodi = auth()->user()->usucodi;
      $c->mescodi = $date->mescodi;
      $c->cotesta =  Cotizacion::STATE_PENDIENTE;
      $c->TidCodi1 = $data['tipo'];
    }

    $c->CotFVta = $data['fecha_emision'];
    $c->CotFVen = $data['fecha_vencimiento'];
    $c->PcCodi  = $data['cliente_documento'];
    $c->ConCodi = $data["forma_pago"];
    $c->zoncodi = $data["ZonCodi"] ?? Zona::DEFAULT_ZONA;
    $c->moncodi = $data["moneda"];
    $c->vencodi = $data["vendedor"];
    $c->DocRefe = $data["doc_ref"];
    $c->cotobse = $data["observacion"];
    $c->CotTCam = $data["tipo_cambio"];
    $c->cotcant = $totales->total_cantidad;
    $c->cotbase = $totales->total_valor_venta;
    $c->cotigvv = $totales->igv;
    $c->cotimpo = $totales->total_cobrado;

    $guardar_individual = ($data['condicion_individual'] ?? 'true') == 'true';
    if ($guardar_individual) {
      $condicion = $data['condicion'] ?? CondicionVenta::getByCotizacion($c->TidCodi1);
    } else {
      $condicion = CondicionVenta::getByCotizacion($c->TidCodi1);
    }

    $c->CotCond = $condicion;
    $c->Cotcont = $data["contacto"];
    $c->CotEsPe = "NP";
    $c->CotPPer = 0;
    $c->CotAPer = 0;
    $c->CotPerc = 0;
    $c->CotTota = $totales->total_cobrado;
    $c->icbper = $totales->icbper;
    $c->cotisc = $totales->isc;
    $c->cotdcto = $totales->descuento_total;
    $c->TipCodi = 111201;
    $c->TidCodi = $data["tipo_documento"];
    $c->save();
    return $c;
  }


  public function nombreDocumento()
  {
    return self::getNombre($this->TidCodi1);
  }

  public function numero()
  {
    return $this->CotNume;
  }

  public function getNombreTipoDocumento()
  {
    return $this->nombreDocumento();
  }

  public static function getPrefix($tidcodi)
  {
    switch ($tidcodi) {
      case self::COTIZACION:
        return self::PREFIX_COTI;
        break;
      case self::PREVENTA:
        return self::PREFIX_PREVENTA;
        break;
      case '98':
        return self::PREFIX_NOTAVENTA;
        break;
      case self::ORDEN_COMPRA:
        return self::PREFIX_ORDENCOMPRA;
        break;        
    }
  }


  public static function getCode($tipo)
  {
    switch ($tipo) {
      case 'preventa':
        return self::PREVENTA;
        break;
      case 'cotizacion':
        return self::COTIZACION;
        break;
      case 'notaventa':
        return self::NOTACOMPRA;
        break;
    }
  }

  public function getRouteIndex($tipo)
  {
    return $this->isOrdenCompra() ?
      route('orden_compras.index') :
      route('coti.index', ['tipo' => $this->TipCodi1]);
  }

  public function getRouteCreate()
  {
    return $this->isOrdenCompra() ? route('orden_compras.create', $this->CotNume) : route('coti.create', $this->CotNume, ['tipo' => $this->TipCodi1]);
  }
  // getRouteCreate

  public function getRouteEdit()
  {
    return $this->isOrdenCompra() ? route('orden_compras.edit' , $this->CotNume ) : route('coti.edit' , $this->CotNume );
  }

  public function getRouteUpdate()
  {
    return $this->isOrdenCompra() ? route('orden_compras.update', $this->CotNume) : route('coti.update', $this->CotNume);
  }


  public function getRouteImprimir()
  {
    return $this->isOrdenCompra() ? 
    route('orden_compras.imprimir', [ 'id' => $this->CotNume, 't_impresion' => '@@', 'formato' => '_FORMATO_']) :
    route('coti.imprimir' , ['id_cotizacion' => $this->CotNume, 'tipo_impresion' => '@@', 'formato' => '_FORMATO_']);
  }

  public static function getNombre($tipo)
  {
    switch ($tipo) {
      case 'preventa':
      case 53:
        return 'Preventa';
        break;
      case 'cotizacion':
      case 'cotizaciones':
      case 50:
        return "COTIZACIÓN";
        break;
      case 'notaventa':
      case 98:
        return 'Orden de Pago';
        break;
      case 99:
        return 'Orden de Compra';
        break;        
      default:
        throw new \Exception("Tipo ({$tipo}) not found", 1);

        break;
    }
  }

  public function getNameLectura()
  {
    return self::getNombre($this->TidCodi1) . ' ' . $this->CotNume;
  }

  public static function getTipoNombre($tipo)
  {
    if ($tipo == null) {
      return $tipo == "cotizaciones";
    }

    if ($tipo == 53 || $tipo == 'preventa') {
      return 'preventa';
    }

    if ($tipo == 53 || $tipo == 'cotizaciones') {
      return 'cotizaciones';
    }

    if ($tipo == 98 || $tipo == 'notaventa') {
      return 'notaventa';
    }
  }


  public function isOrder()
  {
    return $this->TidCodi1 === "98";
  }

  public function isDolar()
  {
    return $this->moncodi != "01";
  }

  /*
  @TODO-IMPORTANT
  */
  public function getDesperdida()
  {
    // if()
    // Esperamos vernos pronto.
    // Esperamos vernos pronto.    
    if($this->EmpCodi == "076"){
      return 'Esperamos vernos pronto.';
    }

    return $this->isOrdenCompra() ?
    'SIN OTRO EN PARTICULAR NOS SUSCRIBIMOS' :
    'SIN OTRO EN PARTICULAR Y ESPERANDO VERNOS FAVORECIDOS CON SUS GRATAS ORDENES, NOS SUSCRIBIMOS DE USTEDES.';
  }

  public function getDpto()
  {
    return $this->isOrdenCompra() ?
      'DPTO. COMPRAS' :
      'DPTO. VENTAS';
  }


  public function dataPdf($formato = PDFPlantilla::FORMATO_A4, $mostrar_igv = true)
  {
    $data = [];
    $data['showVendedor'] = true;
    $data['isPreventa'] = false;

    $showPeso = $this->TidCodi1 != "98";
    $is_cotizacion = $this->isCotizacion();

    if ($this->isOrder()) {
      $data['isOrder'] = true;
      $data['showVendedor'] = false;
      $data['isPreventa'] = true;
    }

    $nombre_documento = strtoupper($this->nombreDocumento());
    $cotizacion = $this->toArray();
    $e = $this->empresa;
    $empresa =  $e->toArray();

    $condiciones_title = "CONDICIONES DE " . $nombre_documento;
    // $condiciones = $is_cotizacion ? explode("-", CondicionVenta::getDefaultCotizacion()) : explode("-", CondicionVenta::getDefaultNotaVenta());
    $condiciones = explode("-", $this->CotCond);
    
    $logo  =  $this->empresa->logoEncode();
    $logo2 = false;
    $cuentas = $e->bancos;
    $bancos = Venta::getFormatBancos($e->bancos->groupBy('BanCodi'));

    $opciones = $e->opcion;
    $decimals = $this->isDolar() ? $opciones->DecDola :  $opciones->DecSole;
    
    $logoMarcaAgua = $e->getLogoEncodeMarcaAgua();
    $logoMarcaAguaSizes = null;
    if ($logoMarcaAgua) {
      $logoMarcaAguaSizes = (new ImgStringInfo($e->FE_RESO))->getInfo();
    }

    $logoSubtitulo = $e->getLogoEncodeSubtitulo();
    $vendedor_nombre = $this->vendedor_()->vennomb;

    if ($this->empresa->isA42() && $this->empresa->EmpLogo1) {
      $logo2 = $this->empresa->logoEncode(2);
    }


    //
    $items = $this->items;
    $empresa['EmpLogo']   = "";
    $empresa['EmpLogo1']  = "";
    $cotizacion['empresa'] = null;
    $data['cuentas'] = $cuentas;
    $data['bancos'] = $bancos;
    $data['title']       = $this->CotNume;
    $data['cotizacion']  = $cotizacion;
    $data['cotizacion2'] = $this;
    $data['vendedor'] = $vendedor_nombre;
    // fecha_emision_
    $data['vendedor_nombre'] = $vendedor_nombre;
    $data['fecha_emision_'] = $this->CotFVta;
    $data['mostrar_igv'] = $mostrar_igv;
    $data['logoMarcaAgua'] = $logoMarcaAgua;
    $data['logoMarcaAguaSizes'] = $logoMarcaAguaSizes;
    $data['logoSubtitulo'] = $logoSubtitulo;
    $data['telefono'] = $this->cliente->PCTel1;
    $data['rubro'] = $e->EmpLin6;
    $data['moneda_abreviatura'] = Moneda::getAbrev($this->moncodi);
    $data['moneda_nombre'] = Moneda::getNombre($this->moncodi);
    $data['decimals'] = $decimals;
    $data['direccion'] =  $e->getLocalDireccion($this->LocCodi);
    $data['telefonos'] = $e->EmpLin4;
    $data['descripcion'] = $this->getDesperdida();
    $data['receptor'] = $this->getDpto();
    
    $data['cliente_correo'] = getNombreCorreo($e->EmpLin3);
    $data['correo'] = $e->EmpLin3;
    $data['logoDocumento']     = $e->getLogo($formato);;
    $data['empresa']     = $empresa;
    $data['condiciones_title'] = $condiciones_title;
    $data['condiciones'] = $condiciones;
    $data['nombre_documento'] = $nombre_documento;
    $data['documento_id'] = $this->CotNume;
    $data['contacto'] = $this->Cotcont;
    $data['orden_campos'] = $e->getOrdenCampos();
    $data['logo']        = $logo;
    $data['logo2']       = $logo2;
    $data['usuario_nombre'] = $this->usuario->usulogi;
    $data['observacion'] = $this->cotobse;
    $data['peso'] = $this->peso();
    $data['base'] = $this->cotbase;
    $data['igv_porcentaje'] = $opciones->Logigv;
    $data['igv'] = $this->cotigvv;
    $data['total'] = $this->CotTota;
    $data['cifra_letra'] = $this->cifra_letra();
    $data['cliente']     = $this->cliente;
    $data['moneda']      = $this->moneda;
    $data['forma_pago']  = $this->forma_pago;
    $data['items']       = $items;
    $data['medio_pago_nombre'] = $this->getMedioPagoNombreForPDF();

    
    $data['showPeso'] = $showPeso;
    return $data;
  }

  public function getMedioPagoNombre()
  {
    return optional($this->medio_pago)->TpgNomb;
  }

  public function getMedioPagoNombreForPDF()
  {
    return ($this->TpgCodi == null || $this->TpgCodi == MedioPago::SIN_DEFINIR) ?
      '' :
      $this->getMedioPagoNombre();
  }


  public function peso()
  {
    return $this->items->sum('DetPeso');
  }

  public function cifra_letra()
  {
    return numero_a_letras($this->cotimpo, 2, $this->moncodi);
  }


  /**
   * Calculo totales
   *
   * @return void
   */
  public function calculateTotals()
  {
    $descuento_total = 0;
    $cantidad_total = 0;
    $isc_total = 0;
    $igv_total = 0;
    $icbper_total = 0;

    $gravada = 0;
    $exonerada = 0;
    $inafecta = 0;
    $gratuita = 0;
    $base = 0;
    $importe = 0;

    # Sumar items            
    foreach ($this->items as $item) {

      $descuento_total += $item->DetDctoV;
      $isc_total    += $item->DetISC;
      $igv_total    += $item->DetIGVP;
      $icbper_total += $item->icbper_value;
      $cantidad_total += $item->DetCant;

      switch (strtoupper($item->DetBase)) {
        case BaseImponible::GRAVADA:
          $gravada += $item->DetImpo;
          $base += $item->getBase();
          break;
        case BaseImponible::INAFECTA:
          $inafecta += $item->DetImpo;
          break;
        case BaseImponible::EXONERADA:
          $exonerada += $item->DetImpo;
          break;
        case BaseImponible::GRATUITA:
          $gratuita += $item->DetImpo;
          break;
      }
    }

    $importe = $gravada + $exonerada + $inafecta + $icbper_total;
    $total = $importe + $gratuita;

    $this->cotcant = decimal($cantidad_total);
    $this->cotigvv = decimal($igv_total);
    $this->cotbase = decimal($base);
    $this->cotimpo = decimal($importe);
    $this->cotdcto = decimal($descuento_total);
    $this->CotTota = decimal($total);
    $this->icbper = decimal($icbper_total);
    $this->cotisc = decimal($isc_total);
    $this->save();
  }

  public function nameFile($ext = '', $unique = false)
  {
    return $this->empresa->EmpLin1 . '-' . ($unique ? $this->CotUni : $this->CotNume) . $ext;
  }


  public function getSerieNumeracion()
  {
    return explode('-',  $this->CotNume)[0];
  }

  public function getSerie()
  {
    return SerieDocumento::findSerie(
      $this->EmpCodi,
      $this->getSerieNumeracion(),
      $this->TidCodi1,
      $this->LocCodi,
      $this->usucodi,
    )
      ->first();
  }


  public function getPlantilla($formato)
  {
    if ($formato == PDFPlantilla::FORMATO_A4) {
      return $this->getSerie()->plantillaA4()->first();
    }

    if ($formato == PDFPlantilla::FORMATO_A5) {
      return $this->getSerie()->plantillaA5()->first();
    }

    if ($formato == PDFPlantilla::FORMATO_TICKET) {
      return $this->getSerie()->plantillaTicket()->first();
    }
  }

  public function generatePDF(
    $formato = PDFPlantilla::FORMATO_A4,
    $generator = PDFGenerator::HTMLGENERATOR,
    $mostrar_igv = true,
    $saveTemp = true,
    $save = false
  ) {
    
    $this->refresh();    
    $empresa = get_empresa();
    $plantilla  = $this->getPlantilla($formato);
    // _dd( $plantilla , $formato);
    // exit();
    $data = $this->dataPdf($formato, $mostrar_igv);
    $pdf = new PDFGenerator(view($plantilla->vista, $data), $generator);
    $namePDF = $this->nameFile('.pdf', true);
    $pdf->generator->setGlobalOptions($plantilla->getSetting($generator));

    if( $formato == PDFPlantilla::FORMATO_TICKET ){
      $pdf->generator->updatePageHeight($data['items']->count(), true);
    }

    // 
    if ($save) {
      FileHelper($empresa->EmpLin1, $empresa->codigo)->save_pdf($namePDF, $pdf->generator->toString());
    }

    if ($saveTemp) {
      $tempPath = file_build_path('temp', $namePDF);
      $pdf->save($tempPath);
      return $tempPath;
    }
  }

  public function deleteItems()
  {
    $isPreventa = $this->isPreventa();
    foreach ($this->items as $item) {
      $item->deleteComplete($isPreventa);
    }
  }

  public function setAnulado()
  {
    if ($this->isPreventa()) {
      foreach ($this->items as $item) {
        $item->eliminateReserved();
      }
    }

    $this->setAnuladoState();
  }


  public function deleteComplete()
  {
    DB::connection('tenant')->beginTransaction();
    $success = true;
    $message = '';

    try {
      $this->deleteItems();
      $this->delete();
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollBack();
      $success = false;
      $message = $th->getMessage();
    }

    return (object) [
      'success' => $success,
      'message' => $message,
    ];
  }

  public function getId()
  {
    return $this->CotNume;
  }

  public function getSerieCorrelativo()
  {
    return explode( '-' , $this->CotNume );
  }

  public function isOrdenCompra()
  {
    return $this->TidCodi1 == self::ORDEN_COMPRA;
  }

  public function updateSeries()
  {
    list($serie, $correlativo) = $this->getSerieCorrelativo();
    SerieDocumento::updateSeries($this->EmpCodi, $this->TidCodi, $serie,  $correlativo);
  }

  public function setFacturado($vtaoper = null)
  {
    (new SetFacturado($this, $vtaoper))->handle();
  }

  public function getZona()
  {
    return optional(Zona::find($this->zoncodi));
  }

  public function zona()
  {
    return $this->hasOne(Zona::class, 'Zoncodi', 'zoncodi' );
  }  

}
