<?php

namespace App\Jobs\Reportes;

use App\Venta;
use App\Empresa;
use App\TipoDocumento;
use App\TipoDocumentoPago;

class VentaContableSireTxt
{
  public $empresa = null;
  public $mescodi = null;
  public $data = null;
  public $content = "";

  public function __construct(Empresa $empresa, $mescodi, $data)
  {
    $this->empresa = $empresa;
    $this->mescodi = $mescodi;
    $this->data = $data;
  }


  // dd( $resumen , $id );
  // fileHelper()->saveTemp( $txtSireExport->getContent(), $txtSireExport->getFileName());
  // return response()->download($txtSireExport->getPath());

  public function generateContent()
  {
    $itemsGroup = $this->data['items'];
    
    $first = false;
    
    foreach ($itemsGroup as $itemGroup) {
      foreach ($itemGroup['items'] as $id => $item) {
        if($first){
          $this->content = $this->getItemData($item, $id);
        }
        else {
          $this->content .= $this->getItemData($item, $id) . "\n";
        }
      }
    }
  }

  public function convertValue( $isSol, $value, $tc )
  {
    if ( floatval($value) == 0) {
      return $value;
    }

    return $isSol ? $value : $value / $tc;

  }

  public function dateFormat($date)
  {

    return implode('/',  array_reverse(explode('/', $date)));
  }


  public function getItemData($item, $id)
  {
    $total = (object) $item['total'];
    $info = (object) $item['info'];


    $isSol = $total->tc == "1";
    $ruc = $this->empresa->ruc();
    $razonSocial = $this->empresa->nombre();
    $periodo = $this->mescodi;
    $carSunat = "";
    $fechaEmision = $info->fecha_emision;
    $fechaVencimiento = $info->fecha_vencimiento;
    $tipoComprobante = $info->tipo_documento;
    $serie = $info->serie;
    $numeroDocumento = $info->numero;
    $numeroFinal = $info->tipo_documento == TipoDocumentoPago::BOLETA ? $info->tipo_documento : "";
    $tipoDocumentoIdentidadCliente = $info->cliente_tipo_documento;
    $numenroDocumentoIdentidadCliente = $info->cliente_documento;
    $nombreCliente = $info->cliente_nombre;
    $dctoDelImpuestoIgv = 0;
    $facturadoExportacion = 0;
    $tipoCambio = $total->tc;
    $baseImponibleGravada = $this->convertValue(  $isSol, $total->base_imponible , $tipoCambio);
    $dctoImponibleGravada = 0;

    if( isset($total->dcto) ){
      $dctoImponibleGravada = $this->convertValue(  $isSol, $total->dcto, $tipoCambio);
    }

    $igv = $this->convertValue( $isSol, $total->igv, $tipoCambio);
    $exonerada = $this->convertValue( $isSol, $total->exonerada, $tipoCambio);
    $inafecta = $this->convertValue( $isSol, $total->inafecta, $tipoCambio);
    $isc = $this->convertValue( $isSol, $total->isc, $tipoCambio);
    $baseImponibleIvap = 0;
    $ivap = 0;
    $icbper = $this->convertValue( $isSol, $total->icbper, $tipoCambio);
    $otrosTributos = 0;
    $importeTotal = $this->convertValue( $isSol, $isSol ? $total->importe_soles : $total->importe_dolares, $tipoCambio); 
    $codigoMoneda = $isSol ? 'PEN' : 'USD';

    $fechaEmisionDocMod = "";
    $tipoComprobanteMod = "";
    $serieComprobanteMod = "";
    $numeroComprobanteMod = "";

    if( $item['info']['tipo_documento'] == TipoDocumentoPago::NOTA_CREDITO || $item['info']['tipo_documento'] == TipoDocumentoPago::NOTA_DEBITO ){
      $doc = Venta::find($id);
      $fechaEmisionDocMod = $doc->VtaTDR;
      $tipoComprobanteMod = $doc->VtaTDR;
      $serieComprobanteMod = $doc->VtaSeriR;
      $numeroComprobanteMod = $doc->VtaNumeR;
    }

    $contratoProyecto = "";
    $nota = "";


    return sprintf(
      '%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s',
      $ruc,
      $razonSocial,
      $periodo,
      $carSunat,
      $this->dateFormat($fechaEmision),
      $this->dateFormat($fechaVencimiento),
      $tipoComprobante,
      $serie,
      $numeroDocumento,
      $numeroFinal,
      $tipoDocumentoIdentidadCliente,
      $numenroDocumentoIdentidadCliente,
      $nombreCliente,
      $facturadoExportacion,
      $baseImponibleGravada,
      $dctoImponibleGravada,
      $igv,
      $dctoDelImpuestoIgv,
      $exonerada,
      $inafecta,
      $isc,
      $baseImponibleIvap,
      $ivap,
      $icbper,
      $otrosTributos,
      $importeTotal,
      $codigoMoneda,
      $tipoCambio == 1 ? '' : $tipoCambio,
      $this->dateFormat($fechaEmisionDocMod),
      $tipoComprobanteMod,
      $serieComprobanteMod,
      $numeroComprobanteMod,
      $contratoProyecto,
      $nota
    );
  }

  public function getContent()
  {
    return $this->content;
  }

  // LE
  // 20552651198 = ruc
  // 2023 = año
  // 07 = mes
  // 00 = para RVIE es 00
  // 140400 = identificador del libro
  // 02 = codigo de oportunidad de presentacion
  // 1 = indicador de operaciones
  // 1 = indicador del contenido del libro o registro
  // 1 = indicador de la moneda utilizada
  // 2 = indicador de libro electronico generado por MIGE IGV/RVIE
  // (N) opcional - Correlativo de los ajustes posteriores 


  public function getFileName()
  {
    $le = "LE";
    $ruc = $this->empresa->ruc();
    $mes = $this->mescodi;
    $rvie = 1;
    $identificadorDelLibro = "140400";
    $codigoOportunidadPresentacion = "02";
    $indicadorDeOperaciones = 1;
    $indicadorContenidoDelLibro = 1;
    $indicadorMoneda = 1;
    $indicadorLibroGeneradoPorMigeIGV = 2;
    $correlativoPorAsjute = "";

    $nameFile =  sprintf(
      '%s%s%s%s%s%s%s%s%s%s%s.txt',
      $le,
      $ruc,
      $mes,
      $rvie,
      $identificadorDelLibro,
      $codigoOportunidadPresentacion,
      $indicadorDeOperaciones,
      $indicadorContenidoDelLibro,
      $indicadorMoneda,
      $indicadorLibroGeneradoPorMigeIGV,
      $correlativoPorAsjute
    );

    return $nameFile;
  }

  public function handle()
  {
    $this->generateContent();
  }
}
