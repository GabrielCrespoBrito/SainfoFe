<?php

namespace App\Models\Venta\Method;

use App\Venta;
use App\Moneda;
use Carbon\Carbon;
use App\PDFPlantilla;
use App\SerieDocumento;
use App\TipoNotaCredito;
use App\TipoDocumentoPago;
use App\Jobs\Resumen\ContentCDR;
use App\Jobs\Venta\CreateAsocGuia;
use App\Models\MedioPago\MedioPago;
use App\Jobs\Venta\CreateNC\CreateNC;
use App\Jobs\Venta\CreateND\CreateND;
use App\Jobs\Venta\DataforNotaDebito;
use App\Jobs\Venta\DataforNotaCredito;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Venta\CreateFormaPagoDiario;
use App\Jobs\Venta\UpdateNotaVentaByCanje;
use App\Util\Sunat\Services\ServicesParams;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Jobs\Venta\PrepareDataVentaForJavascriptPrint;
use App\Jobs\Venta\RegisterPago;
use App\Util\Sunat\Request\credentials\CredentialDatabase;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusResolver;

trait VentaMethod
{  
  public function getMoneda()
  {
    return $this->MonCodi;
  }

  public function hasImported()
  {
    return (bool) $this->VtaPedi;
  }

  public function isCanje()
  {
    return $this->TipoOper == Venta::TIPO_CANJEADA; 
  }

  public function canjes()
  {
    return $this->hasMany( Venta::class, 'VtaOperC', 'VtaOper' );
  }
  
  public function setDefaultState()
  {
    $this->update(['VtaEsta' => "V"]);
  }

  public function setNoSendState($message = false)
  {
    $this->calcularTotales();
    $this->setDefaultState();
    $this->setNoSentState();

    if ($message) {
      return "noSendState to {$this->VtaNume}";
    }
  }

  public function setNoSentState()
  {
    $this->update([
      'fe_rpta' => 9,
      'fe_rptaa' => 2,
      'VtaCDR' => 0,
      'fe_obse' => '',
      'fe_estado' => '',
    ]);
  }

  public function enviosGuiaCerrado()
  {
    return $this->VtaSdCa == 0;
  }

  public static function setNoSendStateMassive()
  {
    foreach (func_get_args() as $vtaoper) {
      $doc = self::find($vtaoper);
      $doc->setNoSendState();
    }
  }

  public function setSentState()
  {
    $documentoNombre = nombreDocumento($this->TidCodi);
    $documentoNumero = $this->VtaNume;

    $this->update([
      'fe_rpta' => 0,
      'fe_rptaa' => 2,
      'VtaCDR' => 1,
      'fe_obse' => sprintf('La %s numero %s, ha sido aceptada', $documentoNombre, $documentoNumero),
      'fe_estado' => $this->getFeEstado(),
    ]);
  }

  public function getFeEstado()
  {
    return 'ESTADO SUNAT(0)';
  }

  public function importedPedido()
  {
    if ($this->hasImported()) {
      $pedido = $this->importacion();
      if (!is_null($pedido)) {
        return $pedido->isNotaVenta() ? $pedido : false;
      }
    }
    return false;
  }

  public static function getContingenciaPendiente()
  {
    $result = ['data' => null, 'result' => false];

    $ventas_contingencia =
      self::where('fe_rptaa', "9")
      ->where('VtaFMail', StatusCode::ERROR_0011['code'])
      ->where('contingencia', 1)
      ->get();

    if ($ventas_contingencia->count()) {
      $result['data'] = $ventas_contingencia;
      $result['result'] = true;
    }

    return (object) $result;
  }

  public function isContingencia()
  {
    return (bool) $this->contingencia;
  }



  public function isNotaVenta()
  {
    return $this->TidCodi == TipoDocumentoPago::NOTA_VENTA;
  }

  public function isProforma()
  {
    return $this->TidCodi == TipoDocumentoPago::PROFORMA;
  }

  /**
   * Confirmar que un documento ya esta enviado
   *
   * @return void
   */
  public function confirmSend()
  {
    $this->update(['fe_rpta' => 0]);
  }

  /**
   * Confiar que un documento  ya esta en un resumen
   *
   * @return void
   */
  public function confirmResumen()
  {
    $this->update(['fe_rptaa' => 0]);
  }

  /**
   * Saber si el cargo global es una percepcion
   * 
   * @return bool
   */
  public function hasPercepcion()
  {
    return $this->hasGlobalCargo() && !$this->hasDescuento();
  }

  /**
   * Saber si el cargo global es una percepcion
   * 
   * @return bool
   */
  public function hasPlaca()
  {
    return optional(optional($this->items)->first())->hasPlaca();
  }

  /**
   * Saber si el cargo global es una percepcion
   * 
   * @return bool
   */
  public function getPlaca()
  {
    return $this->items->first()->getPlaca();
  }

  /**
   * Saber si existe un cargo a nivel global, bien pueda ser descuento o percepcion
   * 
   * @return bool
   */
  public function hasGlobalCargo()
  {
    return (bool) $this->globalPorc();
  }

  /**
   * Porcentaje del cargo
   * 
   * @return float
   * 
   */
  public function globalPorc()
  {
    return $this->VtaPPer;
  }

  /**
   * Si el cargo es un descuento
   * 
   * @return bool
   */
  public function hasDescuento()
  {
    return $this->hasGlobalCargo() && $this->VtaDcto;
  }

  public function descuentoPerc()
  {
    return  $this->VtaPPer;
  }

  public function descuentoFactor()
  {
    return math()->porcFactor($this->descuentoPerc());
  }

  public function tieneDescuento()
  {
    return $this->hasDescuento();
  }

  public function descuentoGlobal()
  {
    return $this->VtaDcto;
  }

  /**
   * Actualizar el tipo de afectacion igv de los items
   */
  public function updatesItems()
  {
    foreach ($this->items as $item) {
      $item->setPorcentajeIGV();
    }
  }

  /**
   * Obtener el numero correlativo si es electronica, si no es electronica, simplemente el id
   *
   * @return string
   */
  public function getNameGuiaCorrelative()
  {
    if (!$this->hasGuiaReferencia()) {
      return '';
    }

    if (!$this->hasGuiaReferenciaElectronica()) {
      $this->GuiOper;
    }

    return $this->guiaReferenciaCorrelative();
  }


  /**
   * Obtener el numero correlativo si es electronica, si no es electronica, simplemente el id
   *
   * @return string
   */
  public function getNameGuiaCorrelativeNew()
  {
    if ($this->Numoper) {
      return $this->Numoper;
    }

    
    
    if (!$this->hasGuiaReferencia()) {
      return '';
    }
    
    if ($this->hasGuiaReferenciaElectronica()) {
      return $this->guiaReferenciaCorrelative();
    }
    

    return '';
  }

  /**
   * Saber si tiene documento referencia 
   * 
   * @return bool
   */
  public function hasGuiaReferencia()
  {
    return (bool) $this->GuiOper;
  }

  /**
   * Saber si tiene documento referencia 
   * 
   * @return bool
   */
  public function hasGuiaReferenciaElectronica()
  {
    return $this->GuiOper ? optional($this->guia)->hasSerie() : false;
  }

  /**
   * Correlativo del numero de referencia
   * 
   */
  public function guiaReferenciaCorrelative()
  {
    return optional($this->guia)->numero();
  }

  /**
   * Si hay detracción en le documento
   * 
   * @return bool
   */
  public function hasDetraccion(): bool
  {
    return (bool) ((int) $this->VtaDetrCode);
  }

  public function detraccionMonto()
  {
    return $this->hasDetraccion() ? $this->VtaDetrTota :  0;
  }

  /**
   * Si hay un anticipo
   * 
   * @return bool
   */

  public function hasAnticipo(): bool
  {
    return (bool) ((int) $this->VtaTotalAnticipo);
  }

  /**
   * 
   * Si el tipo de documento de referencia es factura
   * 
   * @return bool
   */
  public function docReferenciaIsFactura(): bool
  {
    return self::FACTURA === $this->VtaTidCodiAnticipo;
  }

  public function getTotalAttribute()
  {
    return $this->VtaImpo;
  }

  public function getSaldoAttribute()
  {
    return $this->VtaSald;
  }

  public function getParamsConsult($ruc = null)
  {
    $ruc = $ruc ?? $this->empresa->ruc();
    return ServicesParams::getFormatGetStatus($ruc, $this->TidCodi, $this->VtaSeri, (int) $this->VtaNumee);
  }


  public function getCommunicator($cdr)
  {
    $empresa = $this->empresa;

    $resolverConsult =
      new ConsultStatusResolver(
        $empresa->getProveedor(),
        new CredentialDatabase($empresa),
        $empresa->isProduction(),
        $cdr
      );

    return $resolverConsult->getCommunicator();
  }

  public function checkStatus(bool $cdr = true)
  {
    $empresa = $this->empresa;
    $c = new CredentialDatabase($empresa);
    $communicator = $this->getCommunicator($cdr);

    $params = $cdr ? ['parameters' => $this->getParamsConsult()[0]] : $this->getParamsConsult();

    return $communicator
      ->setParams($params)
      ->communicate()
      ->getResponse();
  }


  public function sendSunat($checkStatus = false)
  {
  }

  public function updateStatusCode($statusCode)
  {
    $fe_rpta = 9;
    switch ($statusCode) {
      case StatusCode::CODE_EXITO_0001:
        $fe_rpta = self::FE_RPTA_0;;
        break;
      case StatusCode::CODE_EXITO_0002:
        $fe_rpta = self::FE_RPTA_2;;
        break;
      case StatusCode::CODE_EXITO_0003:
        $fe_rpta = self::FE_RPTA_0;;
        break;
      case StatusCode::CODE_ERROR_0011:
        $fe_rpta = self::FE_RPTA_9;
        break;
      default:
        throw new \Exception("updateStatusCode Error code Processing Request", 1);
        break;
    }

    $this->fe_rpta = $fe_rpta;
    $this->VtaFMail = $statusCode;
    $this->save();
  }

  /**
   * Cuando el documento existe, poner estado correspondiente
   *
   * @return void
   */
  public function saveStatus0001()
  {
    $this->setSentState();
    $this->revertIsAnulada();
  }

  /**
   * Revertir proceso de anulación poniendo la factura en su estado original
   *
   * @return void
   */
  public function revertIsAnulada()
  {
    if ($this->isAnulada()) {
      $this->calcularTotales();
      $this->setDefaultState();
    }
  }

  /**
   * Cuando el documento esta rechazado
   *
   * @return void
   */
  public function saveStatus0002()
  {
    $this->setAnulacioMontos();
    $this->update(['fe_estado' => "RECHAZADO SUNAT"]);
    $this->anularPago();
  }

  /**
   * Cuando el documento esta de baja
   *
   * @return void
   */
  public function saveStatus0003()
  {
    $this->anular();
  }

  /**
   * Cuando el documento no existe
   *
   * @return void
   */
  public function saveStatus0011()
  {
    $this->setDefaultState();
    $this->setNoSentState();
    // $this->setNoSendState();
  }


  public function getStatusCodeAttribute()
  {
    return $this->VtaFMail;
  }

  /**
   * Poner información real dependiendo del tipo de documento.
   *
   * @return void
   */
  public function processStatus()
  {
    switch ($this->statusCode) {
      case StatusCode::CODE_0001:
        $this->saveStatus0001();
        break;
      case StatusCode::CODE_0002:
        $this->saveStatus0002();
        break;
      case  StatusCode::CODE_0003:
        $this->saveStatus0003();
        break;
      case StatusCode::CODE_0011:
        $this->saveStatus0011();
        break;
      default:
        throw new \Exception("statusCode {$this->statusCode} dont has support yet  ", 1);
        break;
    }
  }

  public function isPendiente()
  {
    return $this->VtaFMail == StatusCode::ERROR_0011['code'];
  }

  public function isAceptado()
  {
    return $this->VtaFMail == StatusCode::EXITO_0001['code'];
  }

  public function isRechazado()
  {
    return $this->VtaFMail == StatusCode::EXITO_0002['code'];
  }

  public function isBaja()
  {
    return $this->VtaFMail == StatusCode::EXITO_0003['code'];
  }


  public function searchSunatGetStatus($returnConsult = false, $updateStatusIfNew = true)
  {
    $rpta = $this->checkStatus(false);
    $codeDocument = $this->VtaFMail;
    
    logger(sprintf('@SEARCH-STATUS %s %s %s %s %s', 
    $this->VtaUni,
    $codeDocument,
    $this->EmpCodi,
    $this->User_FModi,
    $this->fe_obse
    ));
    logger($rpta);
    logger('@END-SEARCH-STATUS %s %s %s');

    if ($rpta['client_connection_success']) {
      
      if ($rpta['communicate']) {
        $statusCode = $rpta['commnucate_data']->status->statusCode;
        
        if( $codeDocument != $statusCode ){
          $this->updateStatusCode($statusCode);
          $this->processStatus();
        }
      }
    }

    if ($returnConsult) {
      return $rpta;
    }
  }

  public function searchSunatGetStatusCDR()
  {
    $rpta = $this->checkStatus(true);

    if ($rpta['client_connection_success']) {
      if ($rpta['communicate']) {
        $statusCdr = $rpta['commnucate_data']->statusCdr;
        $rpta['commnucate_data']->cdr_exists = false;
        if (isset($statusCdr->content)) {
          $rpta['commnucate_data']->cdr_exists = true;
          $info = $this->processCDR($statusCdr->content);
          $rpta['commnucate_data']->cdr_info = $info;
        }
      }
    }

    return $rpta;
  }


  /**
   * Procesar CDR 
   * 
   *  @return 
   */
  public function processCDR($content)
  {
    $fileName = $this->nameCdr('.zip');


    $contentCDR = new ContentCDR($content, $fileName, $this->nameCdr('.xml'));
    $info = $contentCDR
      ->saveTemp()
      ->saveCdr()
      ->extraerContent(["cbc:Description", "cbc:ResponseCode"]);


    $fe_obse = preg_replace('/\s+/', ' ', trim($info[0]));

    $this->update([
      'VtaCDR' => 1,
      'fe_obse' => $fe_obse
    ]);

    return (object) [
      'path' => $contentCDR->getTempPath(),
      'info' => $info,
      'fileName' => $fileName,

    ];
  }


  public function updateStatusByResumen($code, $isAnulacion = true)
  {
    if ($code == "0") {
      $code = $isAnulacion ? StatusCode::CODE_0003 : StatusCode::CODE_0001;
      $this->updateStatusCode($code);
      $this->processStatus();
    }

    return $this;
  }

  public function pago()
  {
    return $this->VtaPago;
  }

  public function saldo()
  {
    return $this->VtaSald;
  }

  public function fechaEmision()
  {
    return $this->VtaFvta;
  }

  public function documentoReferencia()
  {
    return $this->DocRefe;
  }

  public function clienteRazonSocial()
  {
    return $this->cliente_with->PCNomb;
  }

  public function estado()
  {
    return $this->VtaEsta;
  }

  public function monedaAbbreviatura()
  {
    return Moneda::getAbrev($this->getMoneda());
  }

  public function condicion()
  {
    return $this->forma_pago->connomb;
  }

  public function getBolsaTotal()
  {
    return $this->icbper;
  }

  public function hasMontoExonerado()
  {
    return (bool) ((float) $this->VtaExon);
  }
  public function hasMontoInafecto()
  {
    return (bool) ((float) $this->VtaInaf);
  }
  public function hasMontoGrauito()
  {
    return (bool) ((float) $this->VtaGrat);
  }
  public function hasMontoISC()
  {
    return (bool) ((float) $this->VtaISC);
  }
  public function hasMontoDcto()
  {
    return (bool) ((float) $this->VtaDcto);
  }
  public function hasMontoICBPER()
  {
    return (bool) ((float) $this->icbper);
  }

  public function getNombreTipoDocucumentoAnticipo()
  {
    return ucwords(strtolower(TipoDocumentoPago::getNombreDocumento($this->VtaTidCodiAnticipo)));
  }

  public function createFormaPago($items = [])
  {
    CreateFormaPagoDiario::dispatchNow($this, $items);
  }

  public function isCredito()
  {
    return $this->forma_pago->isCredito();
  }

  public function totalImpuestos()
  {
    return $this->VtaIGV + $this->VtaISC + $this->icbper;
  }

  public function updateCostosItems()
  {
    foreach ($this->items as $item) {
      $item->updateCosto();
    }
  }


  public function hasMontoPercepcion()
  {
    return ((int) $this->VtaSPer) == Venta::CARGO_PERCEPCION;
  }

  public function hasMontoDctoGlobal()
  {
    return ((int) $this->VtaSPer) == Venta::CARGO_DESCUENTO;
  }  

  public function hasMontoRetencion()
  {
    return ((int) $this->VtaSPer) == Venta::CARGO_RETENCION;
  }

  public function percepcionPorc()
  {
    return $this->VtaPPer;
  }


  public function percepcionMonto()
  {
    return $this->VtaPerc;
  }

  public function retencionPorc()
  {
    return $this->VtaPPer;
  }


  public function retencionMonto()
  {
    $totales = $this->CuenCodi;
    return  $totales['retencion'] ?? 0;
  }

  public function isAvailabledForNotaCredito()
  {
    return 
      ($this->TidCodi == Venta::FACTURA || $this->TidCodi == Venta::BOLETA) &&
      $this->VtaTDR == null &&
      $this->isAceptado() && 
      $this->isAnulada() == false;
  }

  public function isAvailabledForNotaDebito()
  {
    return ($this->TidCodi == Venta::FACTURA || $this->TidCodi == Venta::BOLETA) &&
      $this->VtaTDR == null &&
      $this->isAceptado() &&
      $this->isAnulada() == false;
  }

  public function isAvailabledForNotaDebitoCreditoDemo()
  {
    return ($this->TidCodi == Venta::FACTURA || $this->TidCodi == Venta::BOLETA) &&
      $this->VtaTDR == null &&
      $this->fe_rpta == 0 &&
      $this->isAnulada() == false;    
  }

  public function isValidForConsult()
  {
    return
      $this->TidCodi == Venta::FACTURA ||
      $this->TidCodi == Venta::NOTA_CREDITO ||
      $this->TidCodi == Venta::NOTA_DEBITO;
  }

  public function enPlazoDeAnulacion()
  {
    $dias = config('app.dias_anulacion');
    $carbon = new Carbon($this->VtaFvta);
    return $carbon->addDays($dias)->isAfter(date('Y-m-d'));    
  }

  public function recalculateItems($calculatePrecios = true)
  {
    foreach ($this->items as $item) {
      $item->recalculateTotals($calculatePrecios);
    }
  }

  /**
   * Crear guia asociada a la factura o asociar
   *
   *
   */
  public function createOrAssocGuia($tipo_guia, $guias, $id_almacen = null, $tipo_movimiento = null, $canjeQuery = null )
  {
    $result = (new CreateAsocGuia( $this, $tipo_guia, $guias, $id_almacen, $tipo_movimiento, $canjeQuery ))->handle();

    return $result->getResult();
  }

  public function getStatusMessage()
  {
    return StatusCode::CODES[$this->status_code];
  }

  /**
   * Obtener nombre del tipo de documento
   * 
   * @return string
   * 
   */
  public function getNombreTipoDocumento()
  {

    return ucwords(strtolower(TipoDocumentoPago::getNombreDocumento($this->TidCodi)));
  }

  public function getAnticipoItemData()
  {
    return Venta::where('TidCodi' , $this->VtaTidCodiAnticipo )
    ->where('VtaNume' , $this->VtaNumeAnticipo )
    ->first()
    ->getAnticipoData();
  }



  /**
   * Obtener nombre del tipo de documento
   * 
   * @return string
   * 
   */
  public function getNombreMoneda()
  {

    return ucwords(strtolower(Moneda::getNombre($this->MonCodi)));
  }

  /**
   * Nombre de anticipo
   * 
   * @return string
   */
  public function getNombreAnticipoDocumento()
  {
    return sprintf("ANTICIPO: %s NRO. %s CON FECHA %s",  TipoDocumentoPago::getNombreDocumento($this->TidCodi), $this->VtaNume, $this->VtaFvta);
  }


  /**
   * Nombre de anticipo
   * 
   * @return object
   */
  public function getAnticipoData()
  {
    $item_first = $this->items->first();
    $totales = $item_first->lote;
    $unidad_abreviatura = $item_first->DetUnid;
    $unidad_codi = $item_first->UniCodi;
    $procodi = $item_first->DetCodi;
    $marca = $item_first->MarNomb;
    $cantidad = $item_first->DetCant;
    $precio = $item_first->DetPrec;
    $peso = $item_first->DetPeso;
    $costo_sol = $item_first->DetCSol;
    $costo_dolar = $item_first->DetCDol;
    $valor_sol = $item_first->DetVSol;
    $valor_dolares = $item_first->DetVDol;
    $factor = $item_first->Detfact;
    $igv_unitario = $item_first->DetIGVV;
    $igv_total = $item_first->DetIGVP;
    $importe = $item_first->DetImpo;
    $estado = $item_first->Estado;
    $base = $item_first->DetBase;
    // --------------------------------------------------------------------
    return (object) [
      'unidad_abreviatura' => $unidad_abreviatura,
      'unidad_codi' => $unidad_codi,
      'procodi' => $procodi,
      'nombre_producto' => $this->getNombreAnticipoDocumento(),
      'marca' => $marca,
      'cantidad' => $cantidad,
      'precio' => $precio,
      'peso' => $peso,
      'totales' => $totales,
      'costo_sol' => $costo_sol,
      'costo_dolar' => $costo_dolar,
      'valor_sol' => $valor_sol,
      'valor_dolares' => $valor_dolares,
      'cantidad' => $cantidad,
      'factor' => $factor,
      'igv_unitario' => $igv_unitario,
      'igv_total' => $igv_total,
      'importe' => $importe,
      'estado'  => $estado,
      'base' => $base
    ];
  }


  public static function prepareDataVentaForJavascriptPrint( array $data )
  {
    return (new PrepareDataVentaForJavascriptPrint($data))->convert();
  }

  public function registerPago( $data )
  {
    (new RegisterPago( $this, $data))->handle();
  }


  public function isDocumentoSunat()
  {
    return $this->TidCodi == Venta::FACTURA ||
    $this->TidCodi == Venta::BOLETA ||
    $this->TidCodi == Venta::NOTA_CREDITO ||
    $this->TidCodi == Venta::NOTA_DEBITO;
  }

  public function isAnulable()
  {
    return 
    $this->TidCodi == Venta::FACTURA ||
    $this->TidCodi == Venta::BOLETA ||
    $this->TidCodi == Venta::NOTA_CREDITO ||
    $this->TidCodi == Venta::NOTA_DEBITO ||
    $this->TidCodi == Venta::NOTA_VENTA;
  }

  public static function getFormatBancos( $bancos )
  {
    $data_bancos = []; 

    foreach( $bancos as $banGroup => $cuentas ){
      foreach( $cuentas as $cuenta ){
        $data_cuenta = [
          'banco_nombre' => $cuenta->banco->bannomb,
          'banco_moneda' => Moneda::getAbrev($cuenta->MonCodi),
          'banco_cuenta' => removeWhiteSpace($cuenta->CueNume),
        ];

        $data_bancos[] = $data_cuenta;
      }
    }
    return $data_bancos;
  }

  public function checkIfStatusOrConsult($estadoConsultar, $consultStatus = true  )
  {
    $codeDocumento = $this->VtaFMail;    
    # Si hay que consultar estado, ademas el actual, es diferente es al que hay que consultar
    if( $consultStatus || $codeDocumento != $estadoConsultar ){
      $rpta = $this->checkStatus(false);
      if ($rpta['client_connection_success'] && $rpta['communicate'] ) {
        $codeDocumentoConsult = $rpta['commnucate_data']->status->statusCode;
        if($codeDocumentoConsult != $codeDocumento  ){
          # Actualizar El Estado del Documento
          $codeDocumento = $codeDocumentoConsult;
          $this->updateStatusCode($codeDocumento);
          $this->processStatus();
        }
      }
    }
    
    return (object) [
      'documento' => $this,
      'status_documento' => $codeDocumento,
      'success' => $codeDocumento == $estadoConsultar
    ];
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

  public function hasMedioPago()
  {
    return ! is_null($this->TpgCodi);
  }


  public function deletePdf($recreate, $ruc = null)
  {
    $ruc = $ruc ?? get_empresa()->ruc();
    $namePDF = $this->nameFile('.pdf');
    $fh = fileHelper($ruc);
    if ($recreate) {
      $this->generatePDF(PDFPlantilla::FORMATO_A4, true, false, false, PDFGenerator::HTMLGENERATOR);
    }
    else {
      $fh->deletePDF($namePDF);
    }
  }


  public function getTotalesPago()
  {
    $multiplicador = $this->isSol() ? 1 : $this->VtaTcam;

    $convertNegative = $this->isNotaCredito();
    
    return [
      'importe' => convertNegativeIfTrue($this->VtaImpo * $multiplicador, $convertNegative),
      'pago' => convertNegativeIfTrue($this->VtaPago * $multiplicador, $convertNegative),
      'saldo' => convertNegativeIfTrue($this->VtaSald * $multiplicador, $convertNegative),
    ];
  }


  public function dataForNotaCredito()
  {
    return (new DataforNotaCredito($this))->handle();
  }

  public function dataForNotaDebito()
  {
    return (new DataforNotaDebito($this))->handle();
  }  

  public function createNC( $data )
  {
    return (new CreateNC( $this, $data ))->handle();
  }

  public function createND($data)
  {
    return (new CreateND($this, $data))->handle();
  }

  public function notaCredito()
  {
    return Venta::where('VtaTDR' , $this->TidCodi)
    ->where('TidCodi', TipoDocumentoPago::NOTA_CREDITO  )
    ->where('VtaSeriR' , $this->VtaSeri)
    ->where('VtaNumeR', $this->VtaNumee)
    ->first();
  }

  public function hasNotaCreditoValid()
  {
    return Venta::where('VtaTDR', $this->TidCodi)
    ->where('TidCodi', TipoDocumentoPago::NOTA_CREDITO)
    ->where('VtaSeriR', $this->VtaSeri)
    ->where('VtaNumeR', $this->VtaNumee)
    ->whereIn('VtaFMail', [StatusCode::CODE_0001, StatusCode::CODE_0011] )
    ->first();
  }

  public function notaDebito()
  {
    return Venta::where('VtaTDR', $this->TidCodi)
    ->where('TidCodi', TipoDocumentoPago::NOTA_DEBITO)
    ->where('VtaSeriR', $this->VtaSeri)
    ->where('VtaNumeR', $this->VtaNumee)
    ->first();
  }  
  
  public function interactWithProducts ()
  {
    return $this->vtaadoc == TipoNotaCredito::CODE_01_ANULACION_OPERACION || $this->vtaadoc == TipoNotaCredito::CODE_07_DEVOLUCION_ITEM;
  }

  public function updateSeries()
  {
    SerieDocumento::updateSeries($this->EmpCodi, $this->TidCodi , $this->VtaSeri, $this->VtaNumee );
  }
  
  public function updateNotaVentaByCanje($canjeQuery)
  {
    (new UpdateNotaVentaByCanje($this, $canjeQuery))->handle();
  }

  // public function getSerie()
  // {
  //   return SerieDocumento::findSerie(
  //     $this->EmpCodi,
  //     $this->VtaSeri,
  //     $this->TidCodi,
  //     $this->LocCodi,
  //     $this->UsuCodi
  //   )->first();
  // }

}