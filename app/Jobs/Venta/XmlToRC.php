<?php

namespace App\Jobs\Venta;

use App\Venta;
use App\Moneda;
use App\Resumen;
use App\ClienteProveedor;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\ResumenDetalle;

class XmlToRC
{
  public $pathXml;
  public $fecha_envio;
  public $empresa;
  public $empresa_id;
  public $user;
  public $loccodi;
  public $resumen;
  public $xml;
  public $currentBoleta;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($pathXml, $empresa, $user = null, $loccodi)
  {
    $this->fecha_envio = date ("Y-m-d H:i:s.", filemtime($pathXml));
    $this->pathXml = $pathXml;
    $this->empresa = $empresa;
    $this->empresa_id = $empresa->id();
    $this->loccodi = $loccodi;
    $this->user = $user;

    if (!file_exists($this->pathXml)) {
      throw new \Exception("The xml {$this->pathXml} is not found", 1);
    }

    $this->xml = simplexml_load_file($this->pathXml, 'SimpleXMLElement', LIBXML_NOCDATA);
  }


  public function getNodo($nodoPath)
  {
    return $this->xml->xpath($nodoPath);
  }

  public function getNodoSingleValue($nodoPath)
  {
    $simpleXmlElement = $this->getNodo($nodoPath);

    if (count($simpleXmlElement)) {
      return ((array) $simpleXmlElement[0])[0];
    }

    return null;
  }

  public function getClienteCodigo($boleta)
  {
    $nombre_cliente = 'Simon Bustamantee';
    $documento_cliente = ((array)((array) $boleta->xpath('cac:AccountingCustomerParty/cbc:CustomerAssignedAccountID'))[0])[0];
    $tipo_documento_cliente = ((array)((array) $boleta->xpath('cac:AccountingCustomerParty/cbc:AdditionalAccountID'))[0])[0];
    $documento_cliente =  ($documento_cliente == '-' || $documento_cliente == '') ? '.' : $documento_cliente;
    $tipo_documento_cliente =  ($tipo_documento_cliente == '-' || $tipo_documento_cliente == '') ? '0' : $tipo_documento_cliente;
    $cliente_proveedor = ClienteProveedor::findOrCreateByRuc($documento_cliente, $nombre_cliente, $tipo_documento_cliente, $this->empresa_id, $this->user);
    return $cliente_proveedor->PCCodi;
  }

  public function createVentaCab()
  {
    $boleta = $this->currentBoleta;
    // Obtener fecha
    $fecha_obj =  get_date_info($this->getNodoSingleValue('//cbc:IssueDate'));
    // Obtener hora
    $hora = date('H:i:s');
    // Pedido
    $pedido =  '';
    // guia
    $guia = null;
    // Tipo de documento
    $tipo_documento = ((array)((array) $boleta->xpath('cbc:DocumentTypeCode'))[0])[0];;
    // Nobre documento
    $nombreDocumento = nombreDocumento($tipo_documento);
    // Codigo de cliente
    $pccodi = $this->getClienteCodigo($boleta);
    $correlativo = explode('-', ((array)((array) $boleta->xpath('cbc:ID'))[0])[0]);
    // Moneda
    $moneda = Moneda::SOL_ID;
    $firma = '';

    $tipo_nota = '';
    $tipo_documento_referencia = '';
    $serie_documento_referencia = '';
    $correlativo_documento_referencia = '';
    $fecha_documento_referencia = null;
    $totales = '';


    $cantidad = 1;
    $descuento = 0;
    $base = 0;
    $igv = 0;
    $inafecta = 0;
    $exonerada = ((array)((array) $boleta->xpath('sac:TotalAmount'))[0])[0];
    $gratuita = 0;
    $isc = 0;
    $total = $exonerada;
    $this->totalBoleta = $total;
    $venta = new Venta();
    $venta->VtaOper =  Venta::UltimoId($tipo_documento);
    $venta->EmpCodi = $this->empresa_id;
    $venta->PanAno = $fecha_obj->year;
    $venta->PanPeri = $fecha_obj->month;
    $venta->TidCodi = $tipo_documento;

    $venta->VtaSeri = $correlativo[0];
    $venta->VtaNumee = $correlativo[1];
    $venta->VtaNume = implode('-', $correlativo);

    $venta->VtaFvta = $fecha_obj->full;
    $venta->vtaFpag = null;
    $venta->VtaFVen = $fecha_obj->full;

    $venta->PCCodi = $pccodi;
    $venta->ConCodi = '01';
    $venta->ZonCodi = '0100';
    $venta->MonCodi = $moneda;
    $venta->Vencodi = '1OFI';
    $venta->DocRefe = $venta->VtaNume;

    $venta->GuiOper = '';


    $venta->VtaObse = '';
    $venta->VtaTcam = 4;
    $venta->Vtacant = $cantidad;
    $venta->Vtabase = $base;
    $venta->VtaIGVV = $igv;
    $venta->VtaDcto = $descuento;
    $venta->VtaInaf = $inafecta;
    $venta->VtaExon = $exonerada;
    $venta->VtaGrat = $gratuita;
    $venta->VtaISC = $isc;
    $venta->VtaImpo = $total;
    $venta->VtaEsta = 'V';
    $venta->UsuCodi = $this->user->usucodi;
    $venta->MesCodi = $fecha_obj->mescodi;
    $venta->LocCodi = $this->loccodi;
    $venta->VtaPago = 0;
    $venta->VtaSald = $venta->VtaImpo;
    $venta->VtaPago = 0;
    $venta->VtaEsPe = 'NP';
    $venta->VtaPPer = '0.00';
    $venta->VtaAPer = '0.00';
    $venta->VtaPerc = '0.00';
    $venta->VtaTota = $venta->VtaImpo;
    $venta->TipCodi = '111201';
    $venta->AlmEsta = 'SA';
    $venta->CajNume = 'todo';
    $venta->VtaSdCa = '0';
    $venta->VtaHora = $hora;
    $venta->vtafact = 0;
    $venta->vtaanu = null;
    $venta->vtaadoc = $tipo_nota;
    $venta->VtaPedi = $pedido;
    $venta->User_Crea =  $this->user->usulogi;
    $venta->User_FCrea = $fecha_obj->full . ' ' . $hora;
    $venta->User_ECrea = gethostname();
    $venta->fe_estado = "ESTADO SUNAT(0)";

    $venta->fe_obse =  "La {$nombreDocumento} numero {$venta->VtaNume}, ha sido aceptada";
    $venta->fe_rpta =  0;
    $venta->fe_rptaa =  2;
    $venta->fe_firma =  $firma;
    // Documento Referencia si es NOTA
    $venta->VtaTDR = $tipo_documento_referencia;
    $venta->VtaSeriR = $serie_documento_referencia;
    $venta->VtaNumeR = $correlativo_documento_referencia;
    $venta->VtaFVtaR =  $fecha_documento_referencia;
    //
    $venta->VtaXML = '1';
    $venta->VtaPDF = '1';
    $venta->VtaCDR = '1';
    $venta->VtaMail = 0;
    $venta->VtaFMail = StatusCode::ERROR_0011['code'];
    $venta->Numoper = $guia;
    $venta->TipoOper = 'N';
    $venta->fe_version = null;
    $venta->VtaDetrPorc = null;
    $venta->VtaDetrTota = null;
    $venta->VtaTotalDetr = null;


    $totales = (object) [
      'total_valor_bruto_venta' => 0,
      'total_valor_venta' => 0,
      'valor_venta_por_item_igv' => 0,
      'descuento_global' => 0,
      'total_importe' => 0,
      'impuestos_totales' => 0,
    ];


    $venta->CuenCodi = $totales;
    $venta->VtaDetrCode = '0';
    $venta->VtaAnticipo = '0';
    $venta->VtaNumeAnticipo = '';
    $venta->VtaTidCodiAnticipo = null;
    $venta->VtaTotalAnticipo = 0;
    $venta->contingencia = 0;
    $venta->icbper = 0;

    $venta->save();
    $this->venta = $venta;
  }

  public function createDetalles()
  {
    $boletas = $this->getNodo('//sac:SummaryDocumentsLine');
    foreach ($boletas as $boleta_xml ) {
      
      $correlativo = ((array)((array) $boleta_xml->xpath('cbc:ID'))[0])[0];
      $boleta =  Venta::findByNume($correlativo);
      $item = ((array)((array) $boleta_xml->xpath('cbc:LineID'))[0])[0];
      $item_fixed = $item < 10 ? ("0" . $item) : $item;
      $item++;
      $resumen_detalle = new ResumenDetalle();
      $resumen_detalle->EmpCodi = $this->empresa_id;
      $resumen_detalle->PanAno  = $this->resumen->PanAno;
      $resumen_detalle->PanPeri = $this->resumen->PanPeri;
      $resumen_detalle->numoper = $this->resumen->NumOper;
      $resumen_detalle->docNume = $this->resumen->DocNume;
      $resumen_detalle->DetItem = $item_fixed;
      $resumen_detalle->detfecha = $this->resumen->DocFechaE;
      $resumen_detalle->tidcodi = $boleta->TidCodi;
      $resumen_detalle->detseri = $boleta->VtaSeri;
      $resumen_detalle->DetNume = $boleta->VtaNumee;
      $resumen_detalle->vtatdr = null;
      $resumen_detalle->vtaserir = null;
      $resumen_detalle->vtanumer = null;
      $resumen_detalle->DetMotivo = Resumen::RESUMEN;
      $resumen_detalle->DetGrav = (int) $boleta->VtaExon ? "0.00" : $boleta->Vtabase; 
      $resumen_detalle->DetExon = $boleta->VtaExon;
      $resumen_detalle->DetInaf = $boleta->VtaInaf;
      $resumen_detalle->DetIGV  = $boleta->VtaIGVV;
      $resumen_detalle->DetISC  = $boleta->VtaISC;
      $resumen_detalle->icbper_value = $boleta->getBolsaTotal();
      $resumen_detalle->DetTota = $boleta->VtaImpo;
      $resumen_detalle->PCCodi  = $boleta->PCCodi;
      $resumen_detalle->PCRucc  = $boleta->cliente->PCRucc;
      $resumen_detalle->TDocCodi = $boleta->cliente->TDocCodi;
      $resumen_detalle->VtaEsta = $boleta->VtaEsta;
      $resumen_detalle->save();
    }
  }

  public function updateRC()
  {
    $this->resumen->updateRango();
  }

  public function createRC()
  {
    set_timezone();
    $fecha_generacion = $this->getNodoSingleValue('//cbc:ReferenceDate');
    $fecha_documento = $this->getNodoSingleValue('//cbc:IssueDate');
    $correlative = $this->getNodoSingleValue('//cbc:ID');
    $resumen = new Resumen();
    $resumen->EmpCodi   = $this->empresa_id;
    $resumen->TipoOper  = "R";
    $resumen->NumOper   = Resumen::UltimoId();
    $resumen->DocNume   = $correlative;
    $resumen->DocFechaE = $fecha_generacion;
    $resumen->DocFechaD = $fecha_documento;
    $resumen->DocFechaEv = $this->fecha_envio;
    $resumen->DocMotivo = "R";
    $resumen->DocXML    = 1;
    $resumen->DocPDF    = 1;
    $resumen->DocCDR    = 1;
    $resumen->DocDesc   = '';
    $resumen->DocCEsta  = 0;
    $resumen->DocEstado = "ACEPTADO SUNAT (0)";
    $resumen->User_Crea = $this->user->usulogi;
    $resumen->User_ECrea = gethostname();
    $resumen->MonCodi   = "01";
    $resumen->LocCodi = $this->loccodi;
    $resumen->save();
    $this->resumen = $resumen;
  }


  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->createRC();
    $this->createDetalles();
    $this->updateRC();
  }
}
