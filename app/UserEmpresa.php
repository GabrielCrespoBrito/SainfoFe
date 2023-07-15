<?php

namespace App;

use App\Caja;
use App\Models\UserLocal\UserLocal;
use Illuminate\Database\Eloquent\Model;

class UserEmpresa extends Model
{
  protected $connection = "mysql";
  protected $table = 'usuario_empr';
  public $timestamps  = false;
  public $fillable = ['usucodi','empcodi','estado'];
  
  public function empresa(){
  	return $this->hasOne( Empresa::class , 'empcodi' , 'empcodi' );
  }

  public function user(){
    return $this->hasOne( User::class , 'usucodi' , 'usucodi' );
  }

  public function documentos()
  {
    return $this->hasMany(SerieDocumento::class, 'empcodi', 'empcodi');
  }

  public function assignToDefaultLocal()
  {
    UserLocal::create([
      'usucodi' => $this->usucodi,
      'loccodi' => Local::DEFAULT_LOCAL,
      'empcodi' => $this->empcodi,
      'sercodi' => '0000',
      'numcodi' => '000000',
      'defecto' => 1,
    ]);
  }

  public static function createDefault($empcodi , $usucodi)
  {
    return self::create([
      'usucodi' => $usucodi,
      'empcodi' => $empcodi,
      'estado' => 1,
    ]);
  }

  
  public function createDefaultCaja()
  {
    if( $this->empresa->opcion->OpcConta == 0 ){
      Caja::aperturarTo( $this->usucodi, Local::DEFAULT_LOCAL, $this->empcodi );
    }
  }

}
