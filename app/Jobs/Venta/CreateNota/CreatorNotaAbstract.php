<?php

namespace App\Jobs\Venta\CreateNota;

use App\Venta;
use App\PDFPlantilla;
use App\TipoMovimiento;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\TipoDocumentoPago;

abstract class CreatorNotaAbstract
{
  /**
   * Documento de referencia
   */
  public $documento;

  /**
   * Nota de Credito/Debito
   */
  public $nc = null;

  /**
   * Serie elegida
   */
  public $serie;

  /**
   * Informacion suministrada
   */
  public $data;
  public $notaID;

  public function __construct(Venta $documento, array $data, $notaID)
  {
    $this->documento = $documento;
    $this->serie = $data['serieDocumento'];
    $this->data = $data;
    $this->notaID = $notaID;
  }

  public function createItems()
  {
  }

  function saveSerie()
  {
    $this->serie->updateNextNumber(true);

    return $this;
  }


  public function sendSunat()
  {
    $this->nc->sendSunatPendiente(false);

    return $this;
  }


  public function getDocumentoWithInitialData()
  {
    $empcodi = $this->data['empcodi'];
    $user =  $this->data['user'];
    $loccodi =  $this->data['loccodi'];
    $dates = get_date_info($this->data['fecha']);
    $totales = $this->getTotales();
    $documento = $this->documento;
    $serie = $this->serie;
    $data = $this->data;
    // loremp-ipsum-odlor
    $nc = new Venta;
    $nc->VtaOper = Venta::UltimoId($this->notaID);
    $nc->EmpCodi = $empcodi;
    $nc->PanAno  = $dates->year;
    $nc->PanPeri = $dates->month;
    $nc->TidCodi = $serie->tidcodi;
    $nc->VtaSeri = $serie->sercodi;
    $correlativo = $serie->nextCorrelativo();
    $nc->VtaNumee = $correlativo;
    $nc->VtaNume = $serie->sercodi . "-" . $correlativo;
    $nc->VtaFvta = $data['fecha'];
    $nc->VtaFVen = $data['fecha'];
    $nc->Vtacant = $totales->total_cantidad;
    $nc->PCCodi  = $documento->PCCodi;
    $nc->CuenCodi  = $totales;
    $nc->ZonCodi = "0100";
    $nc->MonCodi = $documento->MonCodi;
    $nc->Vencodi = $documento->Vencodi;
    $nc->DocRefe = $documento->numero();
    $nc->VtaTcam = $documento->VtaTcam;
    $nc->VtaDcto = $totales->descuento_total;
    $nc->VtaISC  = $totales->isc;
    $nc->VtaEsta = "V";
    $nc->UsuCodi = $user->usucodi;
    $nc->MesCodi = $dates->mescodi;
    $nc->LocCodi = $loccodi;
    $nc->VtaPago = 0;
    $nc->VtaEsPe = "NP";
    $nc->VtaSPer = 0;
    $nc->VtaAPer = 0;
    $nc->TipCodi = 111201;
    $nc->AlmEsta = "SA";
    $nc->VtaSdCa = $totales->total_cantidad;
    $nc->VtaHora = date('H:i:s');
    $nc->vtafact = 0;
    $nc->vtaadoc = null;
    $nc->VtaPedi = null;
    $nc->VtaEOpe;
    $nc->User_Crea  = $user->usulogi;
    $nc->CajNume = $this->data['caja_id'];
    $nc->VtaIGVV = $totales->igv;
    $nc->Vtabase = $totales->total_gravadas;
    $nc->VtaSald = $totales->total_cobrado;
    $nc->VtaImpo = $totales->total_cobrado;
    $nc->VtaTota = $totales->total_cobrado;
    $nc->VtaInaf = $totales->total_inafecta;
    $nc->VtaExon = $totales->total_exonerada;
    $nc->VtaGrat = $totales->total_gratuita;
    $nc->icbper = $totales->icbper;
    $nc->VtaISC = $totales->isc;
    $nc->VtaPerc = $totales->percepcion;
    $nc->VtaAPer = $totales->total_base_percepcion;
    $nc->User_ECrea = gethostname();
    $nc->fe_rpta  = 9;
    $nc->fe_rptaa = 2;
    $nc->User_FCrea = date('Y-m-d H:i:s');
    $nc->VtaSeriR = $documento->VtaSeri;
    $nc->VtaTDR   = $documento->TidCodi;
    $nc->VtaNumeR = $documento->VtaNumee;
    $nc->VtaFVtaR = $documento->VtaFvta;
    $nc->VtaXML = 0;
    $nc->VtaPDF = 1;
    $nc->VtaCDR = 0;
    $nc->VtaMail = 0;
    $nc->VtaFMail =  StatusCode::CODE_ERROR_0011;
    $nc->contingencia = 0;
    $nc->GuiOper = "";
    $nc->TipoOper = "N";
    return $nc;
  }

  public function createPagos()
  {
    return $this;
  }

  public function createDataAssociate()
  {
    $this->createPagos();
    // $this->nc->fresh()->saveXML();
    // 
    if ($this->nc->interactWithProducts()) {
      $tipo = $this->notaID == TipoDocumentoPago::NOTA_CREDITO ? TipoMovimiento::DEFAULT_INGRESO : TipoMovimiento::DEFAULT_SALIDA;
      $this->nc->createOrAssocGuia(Venta::GUIA_ACCION_INTERNA, null,  $this->nc->LocCodi, $tipo);
    }
    $this->nc->generatePDF(PDFPlantilla::FORMATO_A4, true, false, false);
    return $this;
  }
}
