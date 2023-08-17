<?php 

namespace App\Http\Controllers\Util\Xml\dos_uno;
use App\Http\Controllers\Util\Xml\XmlHelperNew;

class GuiaRemision_2_1 extends XmlHelperNew
{

  use PartsGuiaXML;

  public function getDocumentoPart()
  {
    $this->change_datas([
      ["codigo", $this->correlativo ],  
      ['fecha' , $this->documento->GuiFemi ],
      ['guiaTypeCode' , "09" ] ,      
      ['nota' , "Nota guia remisión" ] ,
      ['ordenID' , $this->documento->GuiNume ],
      ['ordenType' , "09" ] ,
      ['documentoReferenciaID'  , $this->documento->docrefe ],
      ['documentoReferenciaTipo' , "01" ],
    ],$this->documentsInfo_XMLPART);
  }

  public function getEmpresaPart()
  {
    ## Datos de la empresa
    $this->change_datas([   
      ["ruc_empresa",  $this->ruc_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
    ],$this->firmaData_XMLPART);
    
    $this->change_datas([   
      ["nombrecorto_empresa",  $this->nombrecorto_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
      ["ruc_empresa",  $this->ruc_empresa ],
    ], $this->empresaData_XMLPART);
  }

  public function getClientePart()
  {
    if( $this->documento->isWithProveedor() ){
      $tipo_documento =   $this->empresa->getTipoDocumento();
      $documento =   $this->empresa->getDocumento();
      $nombre = $this->empresa->getNombre();
    } 
    else  {
      $cliente = $this->documento->cliente;
      $tipo_documento = $cliente->getTipoDocumento();
      $documento =   $cliente->getDocumento();
      $nombre = $cliente->getNombre();
    }

    $this->change_datas([   
      ["cliente_tipo_documento",  $tipo_documento ],
      ["ruc_cliente",  $documento ],
      ["nombre_cliente" , $nombre ],
    ], $this->clienteData_XMLPART);
  }


  /**/
  public function getProveedorPart()
  {
    $xml = "";
        
    if( $this->documento->isWithProveedor() ){
      $cliente = $this->documento->cliente;
      $xml = $this->change_datas([   
        ["tipo_documento_proveedor",  $cliente->getTipoDocumento()],
        ["ruc_proveedor",  $cliente->getDocumento() ],
        ["nombre_proveedor" , $cliente->getNombre() ],
      ], $this->proveedorData_base, false);

    }

    $this->proveedorData_XMLPART = $xml;
  }

  public function getShlipmentPart()
  {
    $transportista =  $this->documento->transportista;

    $ruc_transpor = $transportista->TraRucc == "" ? "." : $transportista->TraRucc;    
    $licencia_transpor = $transportista->TraLice == "" ? "." : $transportista->TraLice;
    $nombre_transportista = $transportista->TraNomb;
    $transportista_tipo_documento = typeDocument($transportista->TraRucc);
    $ubigeo_partida = $this->documento->guidisp;
    $ubigeo_llegada = $this->documento->guidisll;

    $this->change_datas([   

      ["motivo_traslado_codigo" , $this->documento->motivoTraslado->MotCodi ],
      ["motivo_traslado_nombre" , $this->documento->motivoTraslado->MotNomb ],      
      ["unidad" , $this->documento->DetUnid ],      
      ["peso"   , $this->documento->guiporp ],
      
      ["ubigeo_partida"    , $ubigeo_partida ],
      ["direccion_partida" , (substr($this->documento->guidirp, 0,99)) ],

      ["nombre_transportista" , $nombre_transportista],
      
      ["ubigeo_llegada"    , $ubigeo_llegada ],      
      ["direccion_llegada" , (substr($this->documento->guidill, 0,99)) ],

      ["transportista_tipodocumento" , $transportista_tipo_documento],
      ["transportista_ruc" , $ruc_transpor  ],
      ["fecha_transporte" , $this->documento->GuiFDes ],
      ["transportista_licencia" , $licencia_transpor ],
      ["placa_vehiculo" , $this->documento->vehiculo->VehPlac ],
      ["cantidad" , $this->documento->guicant  ],      
    ], $this->shipment_XMLPART);
  }

  public function getItemPart()
  {
    $i = 1;
    foreach($this->items as $item){
      $this->items_XMLPART .= 
      $this->change_datas(
        [
          [ "id_item_guia"  , $i ],
          [ "id_item_documento" , $i ],          
          [ "unidad" , $item->DetUnid ],          
          [ "cantidad" , $item->Detcant ],
          [ "nombre_producto"   , $item->DetNomb . ' ' . $item->DetDeta ],
          [ "id_producto", $item->DetCodi ],
        ] , $this->item_base , false 
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