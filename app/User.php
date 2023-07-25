<?php

namespace App;

use PermissionSeeder;
use App\Util\ModelUtil\ModelUtil;
use App\Models\UserLocal\UserLocal;
use App\Models\User\Method\UserMethod;
use App\Notifications\MyResetPassword;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Notifications\Notifiable;
use App\Models\User\Attribute\UserAttribute;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use 
  Notifiable,
  UsesSystemConnection,
  UserAttribute,
  HasRoles, 
  ModelUtil,
  UserMethod;

  protected $table      = 'usuarios';
  protected $primaryKey = 'usucodi';
  protected $keyType = 'string';
  public $incrementing = false;
  const CREATED_AT = 'User_FCrea';
  const UPDATED_AT = 'User_FModi';
  const LOCAL_PRINCIPAL = 1;
  const ID_ADMIN = "01";
  const TIPO_DUENO = "01";
  const TIPO_ASISTENTE = "02";
  const CARGO_ADMNISTRATIVO = "00";
  const PLAN_REGISTER_CAMPO = "ususerf";

  protected $fillable = [ 'usucodi', 'usulogi', 'usucla2','User_FCrea', 'carcodi' , 'usutele', 'usudire', 'ususerf' , 'usunomb', 'usucla1', 'email', 'verificate', 'usercta' ];
  protected $hidden = [ 'password', 'remember_token' ];

  public function checkPermissionTo_($permission, $guard = null)
  {
    return $this->isAdmin() ? true : $this->checkPermissionTo($permission, $guard);
  }


  /**
   * Notificacion para el envio de correo electronico para la recuperación de contraseña
   *
   * @param string $token
   * @return void
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new MyResetPassword($token));
  }

  public static function findByUserName($username)
  {
    return self::where('usulogi', $username )->first();
  }

  public function isContador(){
    return $this->usulogi == "CONTADOR";
  }

  public function username()
  {
    return 'usulogi';
  }

  public function getAuthPassword()
  {
      return $this->usucla2;
  }

  public function empresas()
  {
    return $this->hasMany( UserEmpresa::class, 'usucodi' , 'usucodi' );
  }

  public function local_()
  {
    return \DB::table('usuario_local')
    ->where('usucodi', user_()->usucodi)
    ->where('empcodi', empcodi() )
    ->where('defecto', 1)
    ->first();
  }

  public function codeLocal()
  {
    return substr($this->local_()->loccodi,-1);
  }

  public function getSerieGuia()
  {
    return 'T00' . $this->codeLocal();
  }

  public function getSerieGuiaRemision($tidCodi = GuiaSalida::TIPO_GUIA_REMISION)
  {
    return $this->getDocumento($tidCodi)->first();
  }

  
  public function local()
  {    
    return optional($this->local_())->loccodi;
  }

  public function locales()
  {
    return $this->hasMany(UserLocal::class, 'usucodi' ,'usucodi');
  }

  public function localCurrent()
  {
    return $this->locales->where('defecto', self::LOCAL_PRINCIPAL )->first();
  }

  public function localPrincipal()
  {
    return $this->localCurrent()->local;
  }

  public function cargo()
  {
    return $this->belongsTo( Cargo::class, 'carcodi' , 'CarCodi' );
  }
  
  public function documentos()
  {
    return $this->hasMany( SerieDocumento::class, 'usucodi' , 'usucodi' );
  }

  public function caja_aperturada( $id = true, $local = null )
  {
    $cajas = $this->cajas
    ->where('CajEsta', 'Ap')
    ->where('CueCodi', '0000');

    if( $local ){
      $cajas = $cajas->where('LocCodi' , $local);
    }

    $caja = $cajas->first();
    
    return $id ? 
      optional($caja)->CajNume : 
      $caja; 
  }

  public function caja_aperturada_new($id = true, $local = null)
  {
    return optional(Caja::cajaAperturada( $local, get_empresa()->isTipoCajaLocal() ))->first()->CajNume;
  }

  public function isMaster()
  {
    return $this->cargo->isMaster();
  }

  public function isAdmin()
  {
    return $this->carcodi == "00" || $this->hasRole('admin');
  }

  public function isAdministrative()
  {
    return $this->carcodi == self::CARGO_ADMNISTRATIVO;
  }

  public function nombre()
  {
    return $this->usunomb;
  }

  public function id()
  {
    return $this->usucodi;
  }

  public static function ultimoCodigo()
  {
    $value = self::orderByRaw('CONVERT(usucodi, SIGNED) desc')->first()->usucodi;
    return math()->increment($value);
  }

  public function toggleActive(){
    $this->active = !$this->active;
    $this->save();
    return $this->active;
  }

  public function getDocumento($tidcodi, $serie = false)
  {
    $documentos = $this->documentos->where('tidcodi', $tidcodi);

    if($serie){
      $documentos = $documentos->where('sercodi' , $serie);
    }

    return $documentos;
  }

  public static function getAdmin()
  {
    return self::where('carcodi', '00')->first();
  }

  public function routeNotificationForMail($notification)
  {
    return $this->email;
  }
  
  /**
   * Asignar todos los permisos regulares al usuario
   *
   * @return void
   */
  public function giveAllPermission()
  {
    $permissionSeeder = new PermissionSeeder();
    $permissions = collect($permissionSeeder->getPermissions());
    $permissions = $permissions->where('is_admin', false)->pluck('name');
    $this->givePermissionTo($permissions);
  }

  /**
   * Para ver si un usuario es owner
   */
  public function isOwner()
  {
    return 
    $this->carcodi === "00" ||
    $this->carcodi === self::TIPO_DUENO ||
    $this->carcodi === null;
  }

  public function scopeOwners($query)
  {
    $query->where('carcodi' , self::TIPO_DUENO); 
  }

  public function isLocalSelected($loccodi = null)
  {
    if(  is_null($loccodi)) {
      return false;
    }
  
    return $this->locales->where('loccodi' , $loccodi )->count();
  }

  public function ventas()
  {
    return $this->hasMany( Venta::class, 'UsuCodi',  'usucodi' );
  }

  public function compras()
  {
    return $this->hasMany(Compra::class, 'usuCodi',  'usucodi');
  }

  public function cajas()
  {
    return $this->hasMany(Caja::class, 'UsuCodi', 'usucodi');
  }

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'usucodi',  'usucodi');
  }

}