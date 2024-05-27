<?php

namespace App;

use Exception;
use App\Moneda;
use App\Ubigeo;
use App\Cliente;
use App\SerieDocumento;
use App\Jobs\Guia\Traslado;
use App\Jobs\Guia\UpdateGuia;
use App\Jobs\Guia\GenerateVenta;
use App\Jobs\GuiaSalida\SendApi;
use App\Models\Guia\GuiaIngreso;
use App\Jobs\Guia\CreateFromToma;
use App\Jobs\Guia\GenerateCompra;
use App\Jobs\GuiaSalida\GenerateQr;
use App\Jobs\GuiaSalida\SaveZipCDR;
use App\Jobs\Guia\UpdateGuiaOpenPrice;
use App\Jobs\GuiaSalida\ValidateTicket;
use App\Util\PDFGenerator\PDFGenerator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\InteractPlantilla;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Jobs\GuiaSalida\ApiResponseProcessor;
use App\Jobs\GuiaSalida\GenerateDataEnvioApi;
use App\Models\TomaInventario\TomaInventario;
use App\Models\Guia\Traits\GuiaInteractWithCompra;
use App\Util\XmlInformation\XmlInformationResolver;
use App\Http\Controllers\Util\Xml\dos_uno\GuiaRemision_2_1Api;
use App\Jobs\Guia\CreateFromProduccionManual;
use App\Models\Produccion\Produccion;

class GuiaSalida extends Model
{
  use
    UsesTenantConnection,
    InteractPlantilla,
    XmlInformationResolver,
    GuiaInteractWithCompra;

  protected $table = 'guias_cab';
  protected $primaryKey = 'GuiOper';
  protected $keyType = 'string';
  public $incrementing = false;
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  const NO_PROCESADO = 'NP';
  const INGRESO = 'I';
  const SALIDA = 'S';
  const CERRADA = "C";
  const PENDIENTE = "P";
  const INIT = "000000";
  const INIT_SERI = "00001";
  const INIT_NUME = "000000";

  const CON_FORMATO = 1;
  const SIN_FORMATO = 0;

  const ESTADO_EDIT_CLOSED = "closed";
  const ESTADO_EDIT_OPEN = "open";
  const ESTADO_EDIT_OPEN_PRICE = "open_price";

  # ESTADO GUIA DE TRASLADO
  const ESTADO_TRASLADO_PENDIENTE = "pendiente";
  const ESTADO_TRASLADO_CERRADO = "cerrado";

  # ESTADOS CONFORMIDAD DE GUIA INGRESO EN TRASLADO
  const ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE = "pendiente";
  const ESTADO_CONFORMIDAD_TRASLADO_ACEPTADO = "aceptado";
  const ESTADO_CONFORMIDAD_TRASLADO_RECHAZADO = "observaciones";

  const ESTADO_SUNAT_PENDIENTE = 9;
  const ESTADO_SUNAT_ACEPTADO = 0;
  const ESTADO_SUNAT_ANULADO = 2;
  const ESTADO_API_ESTADO_PENDIENTE = 98;

  # Modalidades de Translado
  const TRASLADO_PUBLICO = "01";
  const TRASLADO_PRIVADO = "02";


  # Tipos de Guia

  const TIPO_GUIA_REMISION = "09";
  const TIPO_GUIA_TRANSPORTISTA = "31";


  const INIT_NUMEE = self::INIT_SERI . '-' . self::INIT_NUME;
  public $fillable = [
    "GuiEsta",
    'GuiOper',
    'GuiCDR',
    'GuiPDF',
    'tracodi',
    'motcodi',
    'VehCodi',
    'guidill',
    'guidirp',
    'GuiSeri',
    'GuiNumee',
    'TidCodi1',
    'Loccodi',
    'cpaOper',
    'docrefe',
    'vtaoper',
    'TraOper',
    'guiobse',
    'guidisp',
    'guidisll',
    'CtoOper',
    'fe_rpta',
    'fe_rpta_api',
    'User_Crea',
    'e_traslado',
    'e_conformidad',
    'obs_traslado',
  ];

  public function sendApi()
  {
    ini_set('max_execution_time', '300');
    $sendApi = new SendApi($this);
    $sendApi->handle();
    return $sendApi->getResult();
  }

  public function isTrasladoPendiente()
  {
    return $this->e_traslado == self::ESTADO_TRASLADO_PENDIENTE;
  }

  public function isTrasladoCerrado()
  {
    return $this->e_traslado == self::ESTADO_TRASLADO_CERRADO;
  }

  public function isConformidadPendiente()
  {
    return $this->e_conformidad == self::ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE;
  }

  public function isConformidadAceptado()
  {
    return $this->e_conformidad == self::ESTADO_CONFORMIDAD_TRASLADO_ACEPTADO;
  }

  public function isConformidadRechazado()
  {
    return $this->e_conformidad == self::ESTADO_CONFORMIDAD_TRASLADO_RECHAZADO;
  }

  /*
    GuiEOpe :Para indicar si esta pediente o si esta cerrada
    P = Pendientes;
    C = Cerrada;
  */

  public function items()
  {
    return $this->hasMany(GuiaSalidaItem::class, 'GuiOper', 'GuiOper');
  }

  public function isSol()
  {
    return $this->moncodi == Moneda::SOL_ID;
  }

  /**
   * Ubigeo de llega del llega de la guia o
   * @return
   */
  public function ubigeoPartida()
  {
    return $this->ubigeo_partida ?? optional($this->almacen)->ubigeo;
  }

  /**
   * Ubigeo de llega del llega de la guia o del cliente
   * @return
   */
  public function ubigeoLlegada()
  {
    return $this->ubigeo_llegada ?? optional($this->cliente)->ubigeo;
  }

  /**
   * Ubigeo de llega del llega de la guia o
   * @return
   */
  public function ubigeoPartidaByClient()
  {
    return $this->ubigeo_partida ?? $this->cliente->ubigeo;
  }

  public function ubigeo_partida()
  {
    return $this->belongsTo(Ubigeo::class, 'guidisp', 'ubicodi');
  }

  public function ubigeo_llegada()
  {
    return $this->belongsTo(Ubigeo::class, 'guidisll', 'ubicodi');
  }

  public function numero()
  {
    return $this->nameDocumento();
  }

  public function isSendSunat()
  {
    return $this->fe_rpta != "9";
  }

  public function nameDocumento()
  {
    return $this->GuiSeri . '-' . $this->GuiNumee;
  }

  public function nameEnvio($ext = ".zip")
  {
    // return $this->empresa->ruc() . "-09-" . $this->nameDocumento() . $ext;
    return sprintf('%s-%s-%s%s', $this->empresa->ruc(), $this->getTipoDocumento(), $this->nameDocumento(), $ext);
  }

  public function namePDF($ruc = null)
  {

    $ruc = $ruc ?? $this->empresa->ruc();
    $nameDocumento = $this->hasSerie() ? $this->nameDocumento() : $this->GuiOper;
    return sprintf('%s-%s-%s.pdf', $ruc, $this->getTipoDocumento(), $nameDocumento);
  }

  public function nameRpta()
  {
    // return 'R-' . $this->nameEnvio(".xml");
  }

  /**
   * Comprobar que una guia se puede adjuntar a una factura
   */
  public static function isSunatAddFactura($guioper)
  {
    $guia = GuiaSalida::find($guioper);
    if (!$guia) {
      return false;
    }

    if ($guia->hasFormato() || $guia->isSalida()) {
      return true;
    }
    return false;
  }

  public function nameCdr()
  {
    return 'R-' . $this->nameEnvio('.zip');
  }

  public function motivoTraslado()
  {
    return $this
      ->belongsTo(MotivoTraslado::class, 'motcodi', 'MotCodi')
      ->withDefault();
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'moncodi', 'moncodi');
  }

  public function vehiculo()
  {
    return $this->belongsTo(Vehiculo::class, 'VehCodi', 'VehCodi');
  }

  public function transportista()
  {
    return $this->belongsTo(Transportista::class, 'tracodi', 'TraCodi');
  }

  public function empresaTransporte()
  {
    return $this->belongsTo(EmpresaTransporte::class, 'TraOper', 'EmpCodi');
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'EmpCodi', 'empcodi');
  }

  public function almacen()
  {
    return $this->belongsTo(Almacen::class, 'Loccodi', 'LocCodi');
  }

  public function cli()
  {
    return $this->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi');
  }

  public function cliente()
  {
    return $this
      ->belongsTo(ClienteProveedor::class, 'PCCodi', 'PCCodi')
      ->withDefault()
      ->where('EmpCodi', $this->EmpCodi)
      ->where('TipCodi', $this->TippCodi);
  }

  public function cliente2()
  {
    return ClienteProveedor::where('PCCodi', $this->PCCodi)
      ->where('EmpCodi', $this->EmpCodi)
      ->where('TipCodi', $this->TippCodi)
      ->first();
  }

  public function cliente_()
  {
    return Cliente::where('PCCodi', $this->PCCodi)
      ->where('EmpCodi', $this->PCCodi);
  }

  public function tipo_doc()
  {
    return "guia";
  }

  public function venta()
  {
    return $this->belongsTo(Venta::class, 'vtaoper', 'VtaOper');
  }

  public function isOnlyRead()
  {
    $v = $this->GuiEOpe;
    return $v == "C";
  }

  public function pendiente()
  {
    return $this->GuiEOpe == "P";
  }

  public static function lastId()
  {
    $last_guia = self::OrderByDesc('GuiOper')
      ->where('EmpCodi', empcodi())
      ->first();

    return $last_guia ? $last_guia->GuiOper : self::INIT;
  }

  public static function lastNume($empcodi, $tipo = "S")
  {
    // First-Dates-First-Dates
    $last_guia =
      self::OrderByDesc('GuiNume')
      ->where('EmpCodi', $empcodi)
      ->where('EntSal', $tipo)
      ->first();
    $value = is_null($last_guia) ? self::INIT_NUME  : $last_guia->GuiNume;
    return $value;
  }

  public static function lastGuiNumee($almacen)
  {
    $guia_ultima = self::OrderByDesc('GuiOper')->first();
    $almacen = Almacen::find($almacen);
    return [
      'serie' => $almacen->SerGuiaSal,
      'nume'  => self::agregate_cero_nume($almacen->NumGuiaSal, 1),
      'numee' => $almacen->SerGuiaSal
    ];
  }

  public static function agregate_cero_nume($numero = false, $set = 0)
  {
    $numero = $numero ? $numero : self::INIT_NUME;
    $cero_agregar = [null, "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);
    return $codigoLen < 8 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public static function agregate_cero($numero = false, $set = 0)
  {
    // ShipMent
    $numero = $numero ? $numero : self::INIT;
    $cero_agregar = [null, "00000", "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);
    return $codigoLen < 8 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public function updateVenta($id_guia)
  {
    $venta = $this->venta;
    $venta->GuiOper = $id_guia;
    // $venta->DocRefe = $this->docrefe;
    $venta->save();
  }

  public function updateDocEnviado()
  {
  }

  public static function createGuia(
    $id_venta = null,
    $is_venta = true,
    $id_almacen = null,
    $id_movimiento = null,
    $setCorrelative = false,
    $observacion = '',
    $fecha = null,
    $is_ingreso = false,
    $tipoDoc = GuiaSalida::TIPO_GUIA_REMISION
  ) {
    set_timezone();
    $serie = "";
    $numero = "";
    $cerradaAbierto = self::PENDIENTE;
    $peso_total = 0;
    $guiaUni = null;
    $zoncodi = Zona::DEFAULT_ZONA;
    $usucodi = auth()->user()->usucodi;

    # Crear la guia a partir de una venta
    if ($is_venta) {
      $vta = Venta::find($id_venta);
      $id_almacen = $id_almacen;
      $estado = $vta->isNotaCredito() ? self::INGRESO : self::SALIDA;
      $id_tipo_movimiento = $id_movimiento;
      $moncodi = $vta->MonCodi;
      $tipo_cambio = $vta->VtaTcam;
      $observacion = $observacion;
      $tipoCliente = ClienteProveedor::TIPO_CLIENTE;
      $guipedi = $vta->VtaPedi;
      $doc_ref = $vta->getCompleteCorrelativo();
      $usucodi = $vta->UsuCodi;
      $tipcodi = $vta->TipCodi;
      $zoncodi = $vta->ZonCodi;
      $vtaoper = $vta->VtaOper;
      $vencodi = $vta->Vencodi;
      $cantidad = $vta->Vtacant;
      $base = $vta->Vtabase;
      $peso_total = $vta->getPesoTotal();
      $tidcodi = $vta->TidCodi;
      $tipoMovimiento = TipoMovimiento::getByCode($id_tipo_movimiento);
    }

    # Creación individual
    else {
      $data = $id_venta;
      $estado = "S";

      $id_almacen = $data['id_almacen'];
      $id_tipo_movimiento = $data['id_tipo_movimiento'];
      $tipoMovimiento = TipoMovimiento::getByCode($id_tipo_movimiento);
      $tipoCliente = $tipoMovimiento == GuiaSalida::SALIDA ? ClienteProveedor::TIPO_CLIENTE : ClienteProveedor::TIPO_PROVEEDOR;

      $cliente = ClienteProveedor::where('PCCodi', $data["cliente_documento"])
        ->where('TipCodi', $tipoCliente)
        ->first();
      $moncodi = $data["moneda"];
      $tipo_cambio = $data['tipo_cambio'];
      $observacion = $data["observacion"];
      $guipedi = $data["nro_pedido"];
      $doc_ref = $data["doc_ref"];
      $mescodi = date('Ym');
      $tidcodi = null;
      $tipcodi = TipoCambioMoneda::ultimo_cambio();
      $vtaoper = null;
      $vencodi = $data["vendedor"];
      $cantidad = "0.00";
      $base     = "0.00";
    }

    $tipoMovimiento = TipoMovimiento::getByCode($id_tipo_movimiento);
    $fechas = get_date_info($fecha ?? date('Y-m-d'));
    $nume = agregar_ceros(self::lastNume($id_almacen), 6);
    $id_guia = self::agregate_cero(self::lastId(), 1);
    $guia = new GuiaSalida;
    $guia->GuiOper = $id_guia;
    $guia->EmpCodi = $empcodi = empcodi();
    $guia->PanAno  = $fechas->year;
    $guia->PanPeri = $fechas->month;
    $guia->mescodi =  $mescodi = $fechas->mescodi;
    $guia->EntSal  =  $tipoMovimiento;

    // _dd( $tipoDoc );
    // exit();

    if ($setCorrelative) {
      $serie_documento = SerieDocumento::getSerie($id_almacen, $empcodi, $tipoDoc);
      $serie = $serie_documento->sercodi;
      $numero = $serie_documento->nextCorrelativo();
      $guiaUni = sprintf('%s-%s-%s', $tipoDoc, $serie, $numero);
    } else {
      if ($is_ingreso) {
        $serie  = strtoupper($data['GuiSeri']);
        $numero = math()->addCero($data['GuiNumee'], 6);
      }
    }

    $guia->GuiSeri  = $serie;
    $guia->GuiNumee = $numero;
    $guia->GuiNume = $nume;
    $guia->GuiUni = $guiaUni;
    $guia->GuiFemi = $fechas->full;
    $guia->GuiFDes = null;
    $guia->TmoCodi = $id_tipo_movimiento;
    $guia->GuiEsta = $estado;
    $guia->PCCodi  = $is_venta ? $vta->PCCodi : $cliente->PCCodi;
    $guia->DCodi   = $guia->PCCodi;
    $guia->zoncodi  = $zoncodi;
    $guia->vencodi = $vencodi;
    $guia->Loccodi = $id_almacen;
    $guia->TidCodi1 = $tipoDoc;
    $guia->moncodi = $moncodi;
    $guia->guiTcam = $tipo_cambio;
    $guia->tracodi = "";
    $guia->guiobse = $observacion;
    $guia->guipedi = $guipedi;
    $guia->guicant = $cantidad;
    $guia->guitbas = $base;
    $guia->guiporp = $peso_total;
    $guia->GuiEsPe = "NP";
    $guia->docrefe = $doc_ref;
    $guia->guidirp = null;
    $guia->guidisp = null;
    $guia->guidill = null;
    $guia->guidisll = null;
    $guia->motcodi = null;
    $guia->VehCodi = null;
    $guia->concodi = "01";
    $guia->mescodi = $mescodi;
    $guia->usucodi = $usucodi;;
    $guia->TipCodi = $tipcodi;
    $guia->cpaOper = NULL;
    $guia->vtaoper = is_numeric($vtaoper) ? agregar_ceros($vtaoper,6,0) : $vtaoper;
    $guia->TidCodi = $tidcodi;
    $guia->IGVEsta = 0;
    $guia->GuiNOpe = null;
    $guia->TraOper = null;
    $guia->GuiEFor = $setCorrelative ? GuiaSalida::CON_FORMATO : GuiaSalida::SIN_FORMATO;
    $guia->GuiEOpe = $cerradaAbierto;
    $guia->TippCodi = $tipoCliente;

    $guia->save();

    if ($is_venta) {
      $guia->updateVenta($id_guia);
    }

    if ($setCorrelative) {
      SerieDocumento::updateDocumentoByGuia($guia);
    }

    return $id_guia;
  }


  public function calculate()
  {
    return true;
  }

  public function calculateTotal()
  {
    // Fuckthanthick
    $items = $this->items;
    $this->guiporp = $items->sum('DetPeso');
    $this->guicant = $items->sum('Detcant');
    $this->guitbas = decimal($items->sum('DetImpo') / math()->baseUno(get_option('Logigv')));
    $this->save();
  }
  public static function getDataCreacion($venta)
  {
    $nro_operacion = null;
    return [
      'nro_operacion' => $nro_operacion,
      'fecha'         => date('Y-m-d'),
      'nro_seri'      => $venta->VtaSeri,
      'nro_docu'      => $venta->VtaNumee,
      'nro_documento' => ($venta->VtaSeri . "-" . $venta->VtaNumee),
    ];
  }

  public function saveDespacho($data)
  {
    $cerrada = self::CERRADA;
    $actualizarSerie = !$this->GuiSeri;
    if ($actualizarSerie) {
      $serie = auth()->user()->getSerieGuiaRemision($this->getTipoDocumento(), $this->Loccodi)->sercodi;

      $numero = SerieDocumento::lastNume($serie, $this->Loccodi, $this->EmpCodi, $this->getTipoDocumento());
      $this->GuiSeri = $serie;
      $this->GuiNumee = $numero;
      $this->GuiUni =  sprintf('%s-%s-%s', $this->getTipoDocumento(), $serie, $numero);
    }

    
    $motivoTraslado = $data['motivo_traslado'] ?? null;

    $this->DCodi = $data['destinatario'] ?? null;
    $this->mod_traslado = $data['modalidad_traslado'] ?? null;
    $this->guidirp = $data['direccion_partida'];
    $this->guiporp = $data['peso_total'];
    $this->guidill = $data['direccion_llegada'];
    $this->guidisp = $data['ubigeo_partida'];
    $this->guidisll = $data['ubigeo_llegada'];
    $this->tracodi =  $data['transportista'] ?? null;
    $this->TraOper = $data['empresa'] ?? null;
    $this->VehCodi = $data['placa'] ?? null;
    $this->GuiEOpe = $cerrada;
    $this->GuiEFor = "1";
    $this->GuiPDF = "0";
    $this->GuiXML = "1";
    $this->GuiFDes = date('Y-m-d');
    $this->motcodi = $motivoTraslado;

    if( $motivoTraslado == MotivoTraslado::IMPORTACION || $motivoTraslado == MotivoTraslado::EXPORTACION ){
      //        "tipo_export" => sprintf("required_if:motivo_traslado,%s,%s|in:50,52",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),
      // "serie_doc_num" => sprintf("required_if:motivo_traslado,%s,%s",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),
      // "export_doc_num" => sprintf("required_if:motivo_traslado,%s,%s",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),

      $this->docrefe = sprintf('%s-%s', $data['tipo_export'] , $data['export_doc_num'] );
    }



      // "tipo_export" => sprintf("required_if:motivo_traslado,%s,%s|in:50,52",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),
      // "serie_doc_num" => sprintf("required_if:motivo_traslado,%s,%s",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),
      // "export_doc_num" => sprintf("required_if:motivo_traslado,%s,%s",MotivoTraslado::IMPORTACION, MotivoTraslado::EXPORTACION),



    $this->e_traslado = $motivoTraslado == MotivoTraslado::TRASLADO_MISMA_EMPRESA ?
      GuiaSalida::ESTADO_TRASLADO_PENDIENTE : null;

    $this->save();

    if ($actualizarSerie) {
      SerieDocumento::updateDocumento($this->GuiOper, 'guia');
    }
  }

  public function successEnvio($sent)
  {
    $content = $sent['content'];
    $data = extraer_from_content($content, "R-" . $this->nameEnvio(".xml"), ["ResponseCode", "DigestValue", "Description"]);
    $this->saveSuccess($data[1], $data[2], $data[0]);
    return $data;
  }

  public function saveSuccess($firma = null, $obse = null, $fe_rpta = 0)
  {
    $firma = $firma ?? str_random(10);
    $obse = $obse ?? "El Comprobante Número {$this->numero()}, ha sido aceptado";
    $fe_rpta = $fe_rpta ?? "0";
    $this->GuiXML = 1;
    $this->GuiCDR = 1;
    $this->fe_firma = $firma;
    $this->fe_obse  = $obse;
    $this->fe_rpta  =  $fe_rpta;
    $this->GuiEOpe  = "C";
    $this->save();
  }

  public function anular()
  {
    $this->cancel(true);
    $this->removeVentaSoc();
    $this->update([
      "GuiEsta" => "A",
      "vtaoper" => null,
      "fe_rpta" => GuiaSalida::ESTADO_SUNAT_ANULADO
    ]);
  }

  public function removeVentaSoc()
  {
    if ($this->hasVentaAsoc()) {

      $this->venta->update([
        'GuiOper' => null
      ]);

      $this->venta->resetProductosPorEnviados();
    }
  }

  public function isCerrada()
  {
    return $this->GuiEOpe == self::CERRADA;
  }

  public function dataQR($newVersion = false)
  {
    if ($this->isSalida() &&  $this->isCerrada()) {
      $data = json_decode($this->fe_rpta_api);
      return optional($data)->qrLink ?? $this->qrOldData();
    }

    return $this->qrOldData();
  }

  public function qrOldData()
  {
    return ($this->empresa->EmpLin1 . '|' .
      '09'                          . '|' .
      $this->GuiSeri                . '|' .
      $this->GuiNumee               . '|' .
      $this->GuiFemi                . '|' .
      $this->GuiFDes                . '|' .
      $this->cliente->TDocCodi      . '|' .
      $this->cliente->PCRucc);
  }



  public function nombreDocumento()
  {
    if ($this->isSalida()) {

      if ($this->isGuiaTransportista()) {
        return 'GUIA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA';
      }

      return $this->hasFormato() ? 'GUIA DE REMISIÓN REMITENTE ELECTRONICA' : 'GUIA DE SALIDA';
    }

    return 'GUIA DE INGRESO';
  }

  public function getNumberCorrelative($withGuioper = true)
  {
    return $this->hasSerie() ? $this->numero() : ($withGuioper ? $this->GuiOper : '');
  }

  public function dataPdf($formato = PDFPlantilla::FORMATO_A4)
  {
    $guia = $this->toArray();
    $firma = $this->dataQR($this->isSalida());
    $e = $this->empresa;
    $empresa =  $e->toArray();
    $empresa['igv_porc'] = $e->opcion->Logigv;
    $bancos = $e->bancos->groupBy('BanCodi');
    $condiciones = explode("\n", CondicionVenta::getDefault());
    $logo  =  $this->empresa->logoEncode();
    $logo2 = $this->empresa->logoEncode(2);
    $onlyShowLogo2 = $this->empresa->fe_formato == 0;
    $allLogos = $this->empresa->fe_formato == 1;
    $empresa['EmpLogo'] = null;
    $guia['empresa'] = null;
    $guia['nombre_documento'] = $this->nombreDocumento();
    //
    $qr = \QrCode::format('png')->size(150)->generate($firma);


    $logoDocumento = $e->getLogo($formato);

    // $hasFormato = $this->hasFormato();
    $hasFormato = $this->hasSerie();
    $numero_documento = $hasFormato ? $this->numero() : $this->GuiOper;

    $data = [
      // 'title'       => $this->nameEnvio('.pdf'),
      'title' => $this->namePDF(),
      'guia' => $guia,
      'guia2' => $this,
      'motivos_traslado' => MotivoTraslado::all(),
      'empresa' => $empresa,
      'hasFormato' => $hasFormato,
      'logoDocumento' => $logoDocumento,
      'isGuiaTransportista' => $this->isGuiaTransportista(),
      'logoMarcaAgua' => null,
      'logoMarcaAguaSizes' => [],
      'moneda_abreviatura' => Moneda::getAbrev($this->moncodi),
      'bancos' => $bancos,
      'bancos' => $bancos,
      'direccion' => $e->getDirecciones(),
      'telefonos' => $e->EmpLin4,
      'cliente_correo' => getNombreCorreo($e->EmpLin3),
      'correo' => $e->EmpLin3,
      'nombre_documento' => $this->nombreDocumento(),
      'documento_id' => $numero_documento,
      //  $this->GuiSeri . '-' . $this->GuiNumee,
      'condiciones' => $condiciones,
      'logo' => $logo,
      'onlyShowLogo2' => $onlyShowLogo2,
      'logo1' => $logo,
      'logo2' => $logo2,
      'allLogos' => $allLogos,
      'cliente' => $this->cliente,
      'moneda' => $this->moneda,
      'forma_pago' => $this->forma_pago,
      'items' => $this->items,
      'qr' => $qr,
      'firma' => $this->fe_firma,
    ];
    return $data;
  }

  public function savePdf()
  {
    $namePdf = $this->namePDF();
    $pdf = \PDF::loadView('guia_remision.pdf', $this->dataPdf());
    // Accepting-Replacing
    // $pdf = PDF::loadView('guia_remision.pdf', $this->dataPdf());
    $pdf->setPaper("a4");

    if ($this->hasFormato()) {
      FileHelper($this->empresa->EmpLin1)->save_pdf($namePdf, $pdf->output());
    }

    return $pdf->stream($namePdf);
  }

  public function generatePdf(
    $save = true,
    $saveTemp = false,
    $formato = PDFPlantilla::FORMATO_A4
  ) {
    $generator = PDFGenerator::HTMLGENERATOR;
    $empresa = $this->empresa;
    $plantilla  = $this->getPlantilla($formato,  $this->isSalida());

    $data = $this->dataPdf($formato);
    $tempPath = '';
    $pdf = new PDFGenerator(view($plantilla->vista, $data), $generator);
    $pdf->generator->setGlobalOptions(PDFGenerator::getSetting($formato, $generator));
    $namePDF = $this->namePDF($empresa->ruc());

    if ($save) {
      FileHelper($empresa->EmpLin1)->save_pdf($namePDF, $pdf->generator->toString());
    }

    if ($saveTemp) {
      $tempPath = file_build_path('temp', $namePDF);
      $pdf->save($tempPath);
      return $tempPath;
    }
  }


  public function isAnulada()
  {
    return $this->GuiEsta === "A"  || $this->fe_rpta === GuiaSalida::ESTADO_SUNAT_ANULADO;;
  }

  /**
   * Borrar todo
   *
   * @return void
   */
  public function deleteComplete()
  {

    $this->isCompra() ?
      optional($this->compra)->resetProductEnviados() :
      optional($this->venta)->updateEnvio();

    $this->cancel(true);
    $this->delete();
  }


  public function nombreEstado()
  {
    if ($this->GuiEFor) {
      switch ($this->fe_rpta) {
        case '0':
          return "Aceptado";
          break;
        case '9':
          return "Pendiente";
          break;
        default:
          return "Con errores";
          break;
      }
    }

    return "-";
  }

  public function correlative()
  {
    return $this->GuiSeri . '-' . $this->GuiNumee;
  }

  /**
   * Poner la serie y el nuevo correlativo de la primera serie de tipo guia que tenga el usuario asociada
   * 
   */
  public function setCorrelativeDefault()
  {
    $documento = user_()->getDocumento("09")->first();
    $local = user_()->local();
    $serie = $documento->sercodi;
    $correlative = agregar_ceros($documento->numcodi, 6, 1);

    $this->update(['GuiSeri' => $serie, 'GuiNumee' => $correlative, 'Loccodi' => $local]);
    SerieDocumento::updateDocumento($this->GuiOper, 'guia');
  }

  public function getguidirpAttribute($val)
  {
    return $this->exists ? $val : optional(auth()->user()->localPrincipal())->LocDire;
  }

  public function compra()
  {
    return $this->belongsTo(Compra::class, 'cpaOper', 'CpaOper');
  }

  public function parent()
  {
    return $this->vtaoper ? $this->venta : $this->compra;
  }

  /**
   * Si el documento padre es una compra;
   * 
   * @return bool
   */
  public function isCompra()
  {
    return (bool) $this->cpaOper;
  }


  public function isSalida()
  {
    return $this->EntSal == self::SALIDA;
  }

  /**
   * Cuando se ha anulado una venta y eliminar una compra, restablecer los items 
   * 
   * @return void
   */

  public function cancel($eliminate = false)
  {

    foreach ($this->items as $item) {
      $procodi = $item->DetCodi;
      if ($eliminate) {
        $item->delete();
      }

      GuiaSalidaItem::updateStockStatic($procodi, $this->Loccodi);
    }
  }

  /**
   * La guia de salida es de ingreso 
   * 
   *  @return bool
   */
  public function isIngreso()
  {
    return $this->EntSal == self::INGRESO;
  }

  /**
   * La guia de salida es de ingreso 
   * 
   *  @return bool
   */
  public function isEgreso()
  {
    return !$this->isIngreso();
  }

  /**
   * Obtener codigo de almacen o local por el Loccodi
   * 
   * @example $this->Loccodi = "001"; return "1"
   * @example $this->Loccodi = "002"; return "2"
   * 
   * @return string
   */
  public function getAlmacen()
  {
    return substr($this->Loccodi, -1);
  }

  /**
   * Ver si la guia tiene serie asociada
   * 
   * @return string
   */
  public function hasSerie()
  {
    return (bool) $this->GuiSeri;
  }

  public function getNombre()
  {
    return $this->hasFormato() ? $this->nameDocumento() : $this->GuiNume;
  }

  public function scopeFormato($query, $value)
  {
    return $query->where('GuiEFor', $value);
  }

  public function scopeMes($query, $value)
  {
    return $query->where('mescodi', $value);
  }

  /**
   * Tipo de guia de salida ingreso o salida
   *
   * @return Query
   */
  public function scopeTipo($query, $value)
  {
    return $query->where('EntSal', $value);
  }

  public function scopeId($query, $value)
  {
    return $query->where('GuiOper', 'LIKE', '%' . $value . '%');
  }
  public function scopeLocal($query, $value = null)
  {
    if ($value) {
      return $query->where('Loccodi', $value);
    }

    return $query;
  }
  public function scopeMotivo($query, $value)
  {
    return $query->where('motcodi', $value);
  }

  public function scopeTipoDocumento($query, $value)
  {
    return $query->where('TidCodi1', $value);
  }

  public function scopeDocReferencia($query, $value)
  {
    return $query->where('docrefe', 'LIKE',  '%' . $value . '%');
  }

  public function scopeNume($query, $value)
  {
    return $query->orWhere('GuiNume', $value);
  }

  public function scopeNumeracion($query, $value)
  {
    return $query->where('GuiNumee', 'LIKE', '%' . $value . '%');
  }

  /**
   * Tipo de guia 
   * @return string
   */
  public function tipoNombre()
  {
    return $this->isIngreso() ? 'Ingreso' : 'Salida';
  }

  /**
   * Route Indice
   * @return string
   */
  public function indexRoute()
  {
    return $this->isIngreso() ? route("guia_ingreso.index") : route("guia.index");
  }

  /**
   * Guia actualización
   * 
   * @return null
   */
  public function updateGuia($request)
  {

    $this->getEstadoEdicion() == self::ESTADO_EDIT_OPEN ?
      UpdateGuia::dispatchNow($this, $request) :
      UpdateGuiaOpenPrice::dispatchNow($this, $request);
  }

  /**
   * Generar documento asociado
   *
   * @param [Request] $request
   * @return void
   */
  public function generateDoc($request)
  {
    $this->isIngreso() ? GenerateCompra::dispatchNow($this, $request) : GenerateVenta::dispatchNow($this, $request);
  }

  public function setPendienteState()
  {
    // return $this->
  }

  /**
   * Si el documento puede modificarse
   *
   * @return boolean
   */
  public function canModify()
  {
    return $this->cpaOper == null &&
      $this->vtaoper == null &&
      $this->CtoOper == null;
  }

  public function is_nulls()
  {
    foreach (func_get_args() as $var) {
      if (!is_null($var)) {
        return false;
      }
    }
    return true;
  }

  /**
   * Guardar relacion con la compra
   * 
   */
  public function saveCompraDocRel($compra)
  {
    $this->update([
      'cpaOper' => $compra->CpaOper,
      'docrefe' => $compra->getCompleteCorrelativo()
    ]);
  }

  /**
   * Guardar relacion con la compra
   * 
   */
  public function saveVentaDocRel($venta)
  {
    $this->update([
      'vtaoper' => $venta->VtaOper,
      'docrefe' => $venta->getCompleteCorrelativo()
    ]);
  }

  /**
   * Si el motivo  
   */
  public function isWithProveedor()
  {
    return MotivoTraslado::isWithProveedor($this->motcodi);
  }

  /**
   * Si tiene algun comprobante asociado
   *
   * @return boolean
   */
  public function hasDoc(): bool
  {
    return (bool) $this->vtaoper;
  }

  /**
   * Si tiene compra asociado
   *
   * @return boolean
   */
  public function hasDocAsoc(): bool
  {
    return  $this->hasVentaAsoc() || $this->hasCompraAsoc();
  }

  /**
   * Si tiene compra asociado
   *
   * @return boolean
   */
  public function hasCompraAsoc(): bool
  {
    return (bool) $this->cpaoper;
  }

  /**
   * Si tiene venta asociado
   *
   * @return boolean
   */
  public function hasVentaAsoc(): bool
  {
    return (bool) $this->vtaoper;
  }

  public function updateDocRefFromDocAsoc()
  {
    $documento = $this->parent();

    if ($documento) {
      $this->hasVentaAsoc() ? $this->saveVentaDocRel($documento)  : $this->saveCompraDocRel($documento);
    }
  }

  public function prepararGuia($guia_id)
  {
    $guia = self::find($guia_id);
    $guidill = optional($guia->cliente)->PCDire;

    if (is_null($guidill)) {
      throw new Exception("Cliente no tiene dirección", 1);
    }

    $this->update([
      'tracodi' => $guia->tracodi,
      'guidirp' => $guia->guidirp,
      'motcodi' => $guia->motcodi,
      'VehCodi' => $guia->VehCodi,
      'guidill' => $guidill,
    ]);
  }

  public static function prepareGuias($ids, $guia_id)
  {
    $ids = (array) $ids;
    foreach ($ids as $id) {
      $guia = self::find($id);
      $guia->prepararGuia($guia_id);
    }
  }

  /**
   * Si la guia tiene formato, es decir tiene una serie asociada
   *
   * @return boolean
   */
  public function hasFormato()
  {
    return $this->GuiEFor == GuiaSalida::CON_FORMATO;
  }

  /**
   * @TODO
   * Metodo para verificarel en que estado se encuentra la guia
   * 
   * @return string  closed|open|open_price
   */
  public function getEstadoEdicion()
  {
    if ($this->hasDoc()) {
      return self::ESTADO_EDIT_CLOSED;
    }

    return $this->hasFormato() ? self::ESTADO_EDIT_OPEN_PRICE : self::ESTADO_EDIT_OPEN;
  }

  public static function createSimply($data, $venta_id)
  {
  }

  public function traslado(array $data)
  {
    $traslado = new Traslado($this, $data);

    return $traslado->handle();
  }

  /**
   * Si la guia ha sido trasladada a otro local
   * 
   * @return bool
   */
  public function haSidoTrasladada()
  {
    return (bool) $this->CtoOper;
  }

  /**
   * Guia de Ingreso Asociada
   * 
   * @return bool
   */
  public function guiaIngreso()
  {
    return $this->belongsTo(GuiaIngreso::class, 'CtoOper', 'GuiOper');
  }

  /**
   * Comprobar si el cliente a quien se esta haciendo traslado, es la misma empresa
   * 
   * @return bool
   */
  public function isSameEmpresa()
  {
    return $this->cliente2()->PCRucc ==  get_ruc();
  }

  public static function createFromToma(TomaInventario $tomaInventario)
  {
    return (new CreateFromToma($tomaInventario, GuiaSalida::SALIDA))->handle();
  }

  public static function createFromProduccionManual(Produccion $produccion, $isIngreso = true)
  {
    return (new CreateFromProduccionManual($produccion, $isIngreso))->handle();
  }



  public function deletePdf($recreate, $ruc = null)
  {
    $ruc = $ruc ?? get_empresa()->ruc();
    $namePDF = $this->namePDF($ruc);
    $fh = fileHelper($ruc);

    if ($recreate) {
      $this->generatePDF(true, false, PDFPlantilla::FORMATO_A4);
    } else {
      $fh->deletePDF($namePDF);
    }
  }

  public function getDataEnvioApi()
  {
    return (new GenerateDataEnvioApi($this))->handle();
  }

  public function createXmlZip()
  {
    $input = new GuiaRemision_2_1Api($this);
    return $input->guardar();
  }

  public function validateTicket()
  {
    return (new ValidateTicket($this))
      ->handle()
      ->getResult();
  }

  /**
   * Guardar el ticket
   */
  public function saveTicket($ticket, $fe_tick_frecepcion)
  {
    $this->fe_ticket = $ticket;
    $this->fe_ticket_frecepcion = $fe_tick_frecepcion;
    $this->save();
  }

  /**
   * 
   * Mas adelante si se implementar guia de tranportista, cambiar 
   */
  public function getTipoDocumento()
  {
    return $this->TidCodi1;
  }

  public function getNotaXML()
  {
    return $this->getTipoDocumento() == GuiaSalida::TIPO_GUIA_REMISION  ?
      'GUIA DE REMISION' :
      'GUIA DE TRANSPORTISTA';
  }

  public function isGuiaTransportista()
  {
    return $this->getTipoDocumento() == GuiaSalida::TIPO_GUIA_TRANSPORTISTA;
  }

  public function isTrasladoPrivado()
  {
    return $this->mod_traslado == self::TRASLADO_PRIVADO;
  }

  public function isTrasladoPublico()
  {
    return $this->mod_traslado == self::TRASLADO_PUBLICO;
  }

  public function generateQR($linkQr = "23409i2034i0234i023423423423424")
  {
    (new GenerateQr($this, $linkQr))->handle();
  }

  public function saveZipCdr($zipCdr)
  {
    return (new SaveZipCDR($this, $zipCdr))->handle();
  }

  public function getModalidadTraslado()
  {
    return $this->mod_traslado;
  }

  public function apiResponseProcess($content)
  {
    return (new ApiResponseProcessor($this, $content))
      ->handle();
  }

  public function getTime()
  {
    return explode(' ', $this->User_FCrea)[1];
  }

  public function modadlidadTrasladoNombre()
  {
    return $this->mod_traslado == self::TRASLADO_PRIVADO ? 'Transporte Privado' : 'Transporte Publico';
  }

  public function canChangeDespacho()
  {
    return $this->fe_rpta == "99" || $this->fe_rpta == "9";
  }

  public function getRouteDespacho()
  {
    return $this->isGuiaTransportista() ? route('guia_transportista.despacho', $this->GuiOper) : route('guia.despacho', $this->GuiOper);
  }

  public static function getNombreRead($tipoDoc)
  {
    return $tipoDoc == GuiaSalida::TIPO_GUIA_REMISION ? 'GUIA DE REMISIÓN' : 'GUIA DE TRANSPORTISTA';
  }

  public static function getRouteStore($tipoDoc)
  {
    return $tipoDoc == GuiaSalida::TIPO_GUIA_REMISION ? route('guia.store') : route('guia_transportista.store');
  }

  public static function getRouteIndex($tipoDoc)
  {
    return $tipoDoc == GuiaSalida::TIPO_GUIA_REMISION ? route('guia.index') : route('guia_transportista.index');
  }

  public function getRouteEdit()
  {
    if ($this->isIngreso()) {
      return route('guia_ingreso.edit', $this->GuiOper);
    }
    return $this->isGuiaTransportista() ? route('guia_transportista.edit', $this->GuiOper) : route('guia.edit', $this->GuiOper);
  }

  public function destinatario()
  {
    return $this
      ->belongsTo(ClienteProveedor::class, 'DCodi', 'PCCodi')
      ->withDefault()
      ->where('EmpCodi', $this->EmpCodi)
      ->where('TipCodi', $this->TippCodi);
  }

  public function getExportData()
  {
    if( $this->isTipoExport() ){
      $docExport =  explode('-', $this->docrefe);


      return (object) [
        'tipo_export' => $docExport[0],
        'documento_id' => sprintf('%s-%s-%s-%s' , $docExport[1]  , $docExport[2], $docExport[3], $docExport[4]),
      ];
    }

    return (object) [
      'tipo_export' => null ?? 52,
      'documento_id' => null,
    ];

  }

  public function isTipoExport()
  {
    return $this->motcodi == MotivoTraslado::IMPORTACION ||
    $this->motcodi == MotivoTraslado::EXPORTACION;
  }

  public function getDocRefReal()
  {
    if($this->motcodi == MotivoTraslado::IMPORTACION || $this->motcodi == MotivoTraslado::EXPORTACION){
      $exportData = $this->getExportData();
      return (object) [
        'id' => $exportData->documento_id ,
        'tipo' =>  $exportData->tipo_export,
      ];
    }

    return (object) [
      'id' => $this->venta->VtaNume ,
      'tipo' =>  $this->venta->TidCodi,
    ];
  }
}
