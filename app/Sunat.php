<?php

namespace App;
use App\Venta;
use App\ErrorSunat;
use App\XmlCreatorResumen;
use App\Http\Controllers\Traits\SunatHelper;
use PhpParser\Node\Scalar\String_;

class Sunat 
{
  public static function sendFactura($id_venta , $save_xml = true , $empcodi = null )
  {
    $venta = is_numeric($id_venta) ? Venta::where('VtaOper', $id_venta)->first() : $id_venta;

    if( $save_xml ){
      $venta->saveXML();
    }
    
    $fileHelper = FileHelper( $venta->empresa->EmpLin1 );
    $nameFile    = $venta->nameFile('.zip');
    $content_zip_firmado = $fileHelper->getEnvio($nameFile);
    $sent = SunatHelper::sent( $nameFile , $content_zip_firmado , false , false, $empcodi );

    if( $sent['status'] ){
      $respuesta = extraer_from_content( $sent['content'] , $venta->nameCdr() , ["ResponseCode","Description"] );
      $venta->successEnvio( $sent['content'] );
      $sent['message'] = substr( $respuesta[1] , 0 , 140 );
      $sent['rpta'] = $respuesta[0];
    }
    else {
      $code = (int) $sent['message'];
      $error = ErrorSunat::find($code);
      $sent['message'] = $sent['message'];
      $sent['rpta'] = $code;
      
      if( $code == 1033 ){
        $venta->fe_rpta = $code;
        $venta->fe_obse = $sent['error_message'];
        $venta->save();
      }
    }

    unset($sent['content']);
    return $sent;   
  }

  public static function sendGuia($id_guia , $empcodi = null)
  {
    $guia = GuiaSalida::find( $id_guia );
    $input = xmlCreador($guia);
    $info = $input->guardar();
    $fileHelper = FileHelper( $guia->empresa->EmpLin1 );
    $nameFile = $guia->nameEnvio('.zip');
    $content_zip_firmado = $fileHelper->getEnvio($nameFile);
    return SunatHelper::sent( $nameFile , $content_zip_firmado , false , true, $guia->EmpCodi );
  }

  public static function verify( $ruc, $tipo_comprobante, $serie, $numero , $name , $solo_verificar = false , $empcodi = null  ){
    
    $params = [
      'rucComprobante' => $ruc,
      'tipoComprobante' => $tipo_comprobante,
      'serieComprobante' => $serie,
      'numeroComprobante' =>  $numero,
    ];  
    
    return SunatHelper::getStatusCdr($params, $name , !$solo_verificar, [], $empcodi );    
  }


  public static function verificarDocument ( $id_venta, $solo_verificar = false , $empcodi = null )
  {
    // $venta = Venta::find( $id_venta , $empcodi );
    $venta =  is_numeric($id_venta) ? Venta::where( 'VtaOper', $id_venta )->first() : $id_venta;

    $sent = self::verify( 
        $venta->empresa->EmpLin1 , 
        $venta->TidCodi , 
        $venta->VtaSeri, 
        $venta->VtaNumee , 
        $venta->nameFile('.zip') , 
        $solo_verificar,
        $empcodi
      );
    
    if($solo_verificar){
      return $sent;
    }

    if( isset($sent['status']) && isset($sent['content']) ) {
      $respuesta = extraer_from_content( $sent['content'] , $venta->nameCdr() , ["ResponseCode", "Description"]);

      $codigo = $respuesta[0];          

      if( $codigo != 0 ){
        $venta->fe_estado = "ENVIADO SUNAT(" . $codigo . ")";
        $venta->VtaCDR = 1;     
        $venta->fe_rpta = $codigo;
        $venta->fe_rptaa = $codigo;     
        $venta->fe_obse = substr($respuesta[1], 0 , 100) ; 
        $venta->save(); 
      }

      $sent['fe_rpta'] = $codigo;       
      $sent['message'] = $respuesta[1]; 
    }

    return $sent;
  }


  public static function anularFactura($ticket)
  {
    return $sent = SunatHelper::ticket($ticket);    
  }

  public static function sentAnulacion($id ,$type, $docnume  )
  {
    return self::sentResumenOAnulacion( $id , $type , $docnume , empcodi());
  }

  public static function sentResumen($id)
  {
    return self::sentResumenOAnulacion($id);
  }

  public static function sentResumenOAnulacion( $numcodi , $isResumen = true , $docnume = null , $empcodi = null  )
  {
    if( $empcodi ){
      $resumen = Resumen::findMultiple( $numcodi , $docnume , $empcodi );
    }
    else {
      $resumen = Resumen::find($numcodi );
    }

    $input = $isResumen ? new XmlCreatorResumen( $resumen , $empcodi ) : new XmlCreatorBaja($resumen);
    $data = $input->guardar();
    $path = $data['path'];
    $content = file_get_contents($path);  

    // Enviar a la a sunat
    $sent = SunatHelper::sent( $resumen->nameFile(false,'.zip') ,$content);   

    $msg = $sent['message'];

    if($sent['status']){
      $isResumen ? 
      $resumen->successEnvio($msg) : 
      $resumen->successEnvioAnulacion($msg);
    }
    else {
      if($resumen){
        $resumen->grabarError($sent);
      }
    }

    return $sent;
  }


  public static function verificarTicket($ticket, $nameFile)
  {
    return SunatHelper::ticket($ticket, $nameFile);
  }

  public static function sentPendiente( $id , $empcodi = null )
  {
    $venta = is_numeric($id) ? Venta::where( 'VtaOper' , $id )->first() : $id;
    $empresa = $venta->empresa;
    $empcodi = $empresa->empcodi;

    
    return self::sendFactura($id, true, $empcodi);
    
    
    // if($empresa->produccion()){
      
      // $sent = self::verificarDocument( $id, false , $empcodi );
      
    //   if(  $empresa->is_ose()  ){
    //     if ($sent['status'] && $sent['message'] != "0") {
    //       $sent = self::sendFactura($id, true, $empcodi);
    //     }
    //   }

    //   else {
    //     if ( 
    //       $sent['status'] && ($sent['message'] == "127" || $sent['message'] == "0125" || $sent['message'] == 125 || $sent['message'] == 127  )  ) {
    //       $sent = self::sendFactura($id, true, $empcodi);
    //     }
    //   }
    // }

    // else {
    //   $sent = self::sendFactura($id , true , $empcodi);
    // }
    
    // return $sent;    
  }

}