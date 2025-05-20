<?php

namespace App\Http\Controllers\Util\Xml\dos_uno;

use App\Http\Controllers\Util\Xml\XmlHelperNew;
use App\Http\Controllers\Util\Xml\dos_uno\PartsGuiaXMLApi;
use App\MotivoTraslado;

class GuiaRemision_2_1Api extends XmlHelperNew
{
  use PartsGuiaXMLApi;

  public function getDocumentoPart()
  {
    $this->change_datas([
      ["codigo", $this->correlativo],
      // ['fecha', $this->documento->GuiFemi],
      ['fecha', date('Y-m-d') ],
      ['tiempo', $this->documento->getTime()],
      ['guiaTypeCode', $this->documento->getTipoDocumento() ],
      ['nota', $this->documento->getNotaXML() ],
      ['ordenID', $this->documento->GuiNume],
      ['ordenType', "09"],
      ['documentoRelacionado', $this->getDocumentoRelacionado() ],
    ], $this->documentsInfo_XMLPART, true);
  }

  public function getDocumentoRelacionado()
  {
    
    if($this->documento->vtaoper || $this->documento->isTipoExport()){
      $docRelacionado = $this->documento->getDocRefReal();

      // @TODO: el documento relacionado tiene que ser la factura que emite el cliente
      $ruc = $this->documento->isWithProveedor() ? $this->documento->cliente->getDocumento() : $this->ruc_empresa;

      return $this->change_datas([
        ['id', $docRelacionado->id  ],
        ['tipo', $docRelacionado->tipo ],
        ['tipo_nombre', $this->documento->xmlInfo()->documentoRelacionadoNombre($docRelacionado->tipo)],
        ['ruc', $ruc],
      ], $this->documentoRelacionado, false);
    }
    return '';
  }

  public function getEmpresaPart()
  {
    ## Datos de la empresa
    $this->change_datas([
      ["ruc_empresa",  $this->ruc_empresa],
      ["nombre_empresa", $this->nombre_empresa],
    ], $this->firmaData_XMLPART);

    $this->change_datas([
      ["nombrecorto_empresa",  $this->nombrecorto_empresa],
      ["nombre_empresa", $this->nombre_empresa],
      ["ruc_empresa",  $this->ruc_empresa],
    ], $this->empresaData_XMLPART);
  }

  public function getClientePart()
  {
    if ($this->documento->isWithProveedor()) {
      $tipo_documento =   $this->empresa->getTipoDocumento();
      $documento =   $this->empresa->getDocumento();
      $nombre = $this->empresa->getNombre();
    } else {
      $cliente = $this->documento->cliente;
      $tipo_documento = $cliente->getTipoDocumento();
      $documento =   $cliente->getDocumento();
      $nombre = $cliente->getNombre();
    }

    $this->change_datas([
      ["cliente_tipo_documento",  $tipo_documento],
      ["ruc_cliente",  $documento],
      ["nombre_cliente", $nombre],
    ], $this->clienteData_XMLPART);
  }

  public function getInfoRemitente()
  {
    // ["tipo_documento_proveedor",  $cliente->getTipoDocumento()],
    // ["ruc_proveedor",  $cliente->getDocumento()],
    // ["nombre_proveedor", $cliente->getNombre()],

    if ($this->documento->isGuiaTransportista()) {
      $cliente = $this->documento->cliente;
      return $this->change_datas(
        [
          ["tipo_documento",  $cliente->getTipoDocumento()],
          ["documento", $cliente->getDocumento() ],
          ["nombre", $cliente->getNombre() ],
        ],
        $this->despatch_base,
        false
      );
    }
    return '';
  }


  /**/
  public function getProveedorPart()
  {
    $xml = "";

    if ($this->documento->isWithProveedor()) {
      $cliente = $this->documento->cliente;
      $xml = $this->change_datas([
        ["tipo_documento_proveedor",  $cliente->getTipoDocumento()],
        ["ruc_proveedor",  $cliente->getDocumento()],
        ["nombre_proveedor", $cliente->getNombre()],
      ], $this->proveedorData_base, false);
    }

    $this->proveedorData_XMLPART = $xml;
  }




  public function getEmpresaTransporteData()
  {
    if( $this->documento->isTrasladoPublico() || $this->documento->isGuiaTransportista() ){
      $empresaTransporte = $this->documento->empresaTransporte;
      return $this->change_datas([
        ["documento", $empresaTransporte->getDocumento() ],
        ["nombre", $empresaTransporte->getNombre()  ],
        ["mtc", $empresaTransporte->getRegistroMTC() ],
      ], $this->empresaTransporteXML, false);
    }

    return '';
  }

  public function getTransportistaData()
  {
    if ($this->documento->isTrasladoPrivado() || $this->documento->isGuiaTransportista()) {
      $transportista = $this->documento->transportista;
      return $this->change_datas([
        ["documento", $transportista->getDocumento()],
        ["tipo_documento", $transportista->getTipoDocumento()],
        ["nombres", $transportista->getNombres()],
        ["apellidos", $transportista->getApellidos()],
        ["licencia", $transportista->getLicencia()],
      ], $this->transportistaXML, false);
    }
    return '';
  }

  public function getConstanciaInscripcion()
  {
    if ($this->documento->isGuiaTransportista()) {
      return $this->change_datas([
        ["constancia", $this->documento->vehiculo->getConstanciaInscripcion()],
      ], $this->constanciaInscripcion_base, false);
    }
    
    return '';
  }

  public function getVehiculoData()
  {
    if ( $this->documento->isTrasladoPrivado() || $this->documento->isGuiaTransportista() ) {
      return $this->change_datas([
        ["placa", $this->documento->vehiculo->getPlaca()],
        ["constancia_inscripcion", $this->getConstanciaInscripcion() ],
        
      ], $this->vehiculoXML, false);
    }

    return '';
  }

  public function getEstablecimientoAnexo($isLlegada = true)
  {
    // Hasta ahora solo se necesita para establecimientos de la misma empresa
    if( $this->documento->motcodi == MotivoTraslado::TRASLADO_MISMA_EMPRESA ){
      return $this->change_datas([
        ["ruc", $this->ruc_empresa ],
        ["codigo", "0000"  ],
      ], $this->e_anexo_base, false);

    }

    return '';
  }


  public function getMotivoTransporte()
  {
    if ($this->documento->isGuiaTransportista()) {
      return '';
    }

    return $this->change_datas([
      ["motivo_traslado_codigo", $this->documento->motcodi ],
    ], $this->motivoTransporte_base, false );

  }

  public function getModalidadTransporte()
  {
    if ($this->documento->isGuiaTransportista()) {
      return '';
    }

    return $this->change_datas([
      ["modalidad_transporte", $this->documento->mod_traslado],
    ], $this->modalidadTransporte_base, false );

    // ["motivo_traslado_codigo", $this->documento->motcodi],
    // ["modalidad_transporte", $this->documento->mod_traslado],
  }

  public function getMotivoTransporteOtros()
  {
    // Hasta ahora solo se necesita para establecimientos de la misma empresa
    if ($this->documento->motcodi == MotivoTraslado::OTROS) {
      return $this->change_datas([
        ["instrucciones", 'INSTRUCCIONES DE TRASLADO OTROS'],
      ], $this->motivoTrasladoOtros, false);
    }

    return '';
  }
  

  public function getShlipmentPart()
  {
    // cbc:HandlingInstructions
    $ubigeo_partida = $this->documento->guidisp;
    $ubigeo_llegada = $this->documento->guidisll;
    // 
    $this->change_datas([
      // -----------------------------------------------------------
      ["motivo_traslado_codigo", $this->getMotivoTransporte()],
      ["motivo_traslado_otros", $this->getMotivoTransporteOtros()],
      
      ["modalidad_transporte", $this->getModalidadTransporte() ],
      // -----------------------------------------------------------
      ["unidad", $this->documento->DetUnid],
      ["peso", $this->documento->guiporp],
      // -----------------------------------------------------------
      // ["fecha_transporte", $this->documento->GuiFDes],
      ["fecha_transporte", date('Y-m-d') ],
      ["cantidad", $this->documento->guicant],
      // -----------------------------------------------------------
      ["ubigeo_partida", $ubigeo_partida],
      ["direccion_partida", (substr($this->documento->guidirp, 0, 99))],
      // -----------------------------------------------------------
      ["establecimiento_anexo_llegada", $this->getEstablecimientoAnexo() ],
      ["establecimiento_anexo_partida", $this->getEstablecimientoAnexo(false)],
      //
      ["ubigeo_llegada", $ubigeo_llegada],
      ["direccion_llegada", (substr($this->documento->guidill, 0, 99))],
      // 
      ['info_remitente' , $this->getInfoRemitente() ],
      //
      ["empresa_transporte_data", $this->getEmpresaTransporteData() ],
      ["transportista_data", $this->getTransportistaData()],
      ["vehiculo_data", $this->getVehiculoData()],      
      // 
    ], $this->shipment_XMLPART);
  }

  public function getItemPart()
  {
    $i = 1;
    foreach ($this->items as $item) {
      $this->items_XMLPART .=
        $this->change_datas(
          [
            ["id_item_guia", $i],
            ["id_item_documento", $i],
            ["unidad", $item->DetUnid],
            ["cantidad", $item->Detcant],
            ["nombre_producto", $item->DetNomb . ' ' . $item->DetDeta],
            ["id_producto", $item->DetCodi],
          ],
          $this->item_base,
          false
        );
      $i++;
    }
  }

  public function setDatasPartsXml()
  {
    $this->getDocumentoPart();
    $this->getEmpresaPart();
    $this->getClientePart();
    $this->getProveedorPart();
    $this->getShlipmentPart();
    $this->getItemPart();
    return;
  }
}