<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificacionDocumentosPendientes extends Model
{
  protected $table = "documentos_pendientes";
  public $timestamps = false;
  // Lapsos de tiempo
  const LAPSO_HOY = 'hoy';
  const LAPSO_TODO = 'todo';
  const LAPSO_VENCER = "por_vencer";    
  // Tipos de documentos
  const BOLETA = 'boleta';
  const FACTURA = 'factura';
  // --------------------------------------------------------------
  protected $fillable = [ "cantidad" , "lapso" , "tipo_documento" ];
  public $data = [];

  public static function get_info()
  {
    $data = [
      'por_vencer' => false,
      'total' => 0,
      'boletas_hoy' => 0,
      'boletas_todo' => 0,
      'boletas_vencer' => 0,
      'facturas_hoy' => 0,
      'facturas_todo' => 0,
      'facturas_vencer' => 0,
    ];
    
    return $data;
  }

  public static function getEspecificTipo( $tipo_documento , $lapso )
  {
   return self::where( 'EmpCodi' , get_empresa('id') )
    ->where('lapso', $lapso)
    ->where('tipo_documento', $tipo_documento)    
    ->first();
  }

  public static function get_values( $data , $tipo_documento, $lapso)
  {
    $data =
    $data
    ->where('tipo_documento', $tipo_documento)
    ->where('lapso', $lapso)
    ->where('EmpCodi', get_empresa('id') )    
    ->first();

    if(!is_null($data)){
      return $data->cantidad;
    }

    return 0;
  }


  public static function getBoletasTotales()
  {
    $value = 0;
    $id_empresa = get_empresa('id');
    
    self::where('EmpCodi' , $id_empresa )
    ->where('tipo_documento' , self::BOLETA )
    ->where('lapso' , self::LAPSO_TODO )
    ->first();

    return $value;
  }

  public function detalles()
  {
    return $this->hasMany( DocumentosPendienteDetalle::class, 'id_documento_pendiente' , 'id' );
  }

  public function get_tipo( $documento = true , $lapso = 'hoy' )
  {
    return $this->count();
    $documentos = $this->where('lapso' , $lapso );
  }

  public function boletas_hoy( $cantidad )
  {
    self::where('tipo_documento' , '03');
  }

  public function get_data($tipo_documento, $lapso, $cantidad)
  {
    return [
      'tipo_documento' => $tipo_documento,
      'lapso' => $lapso,
      'cantidad' => $cantidad,  
      'EmpCodi' => get_empresa('id')
    ];
  }

  public function get_notificacion( $tipo , $lapso )
  {
    $notificacion = self::where('lapso' , $lapso );

    if( $tipo == self::FACTURA ){
      $notificacion = $notificacion->where('tipo_documento', '!=' , self::BOLETA );          
    }
    else {
      $notificacion = $notificacion->where('tipo_documento' , self::BOLETA);    
    }    
    return $notificacion->first() ?? new self();
  }

  public function save_data($notificacion , $data)
  {
    $notificacion->cantidad = $data['cantidad'];
    $notificacion->tipo_documento = $data['tipo_documento'];
    $notificacion->lapso = $data['lapso'];  
    $notificacion->save();

    return $notificacion;
  }


  public function RegisterFactura( $lapso ,  $cantidad , $documentos ){
    $this->Register( self::FACTURA, $lapso , $cantidad , $documentos );
  }

  public function RegisterBoleta( $lapso ,  $cantidad , $documentos){
    $this->Register( self::BOLETA, $lapso , $cantidad , $documentos );
  }

  public function Register( $tipo_documento , $lapso ,  $cantidad , $documentos)
  {
    $data = $this->get_data( $tipo_documento, $lapso,$cantidad );
    $notificacion = $this->get_notificacion( $tipo_documento, $lapso );    
    $noti_registro = $this->save_data( $notificacion, $data );

    if( $noti_registro->detalles->count() ) {
      $noti_registro->detalles()->delete();
    }

    $noti_registro->registrarDocumentos($documentos);
  }

  public $i = 0;

  public function registrarDocumentos($documentos)
  {
    if( $documentos->count() ){
      $id = $this->id ;
      $registros = $documentos->map(function($item,$key) use($id) {
        return  ['id_documento_pendiente'  => $id , 'VtaNume' => $item->VtaNume , 'VtaOper' => $item->VtaOper ];
      })->toArray();

      $this->detalles()->createMany($registros);
    }
  }

}
