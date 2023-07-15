<?php

namespace App\Models\User\Method;

use App\User;
use App\Local;
use App\UserEmpresa;
use PermissionSeeder;
use App\SerieDocumento;
use Illuminate\Http\Request;
use App\Jobs\User\RegisterLocales;
use App\Jobs\User\NotificacionData;
use App\Jobs\User\RegisterPermission;
use App\Jobs\User\UpdateRegisterLocales;
use App\Util\SendMessage\SendMessageNexmo;

trait UserMethod
{
  public function boletasSeries()
  {
    return $this->documentos
      ->where('tidcodi', '03');
  }

  public function isVerificated()
  {
    return $this->verificate == 1;
  }

  public function canModifyPrecios($venta = true)
  {
    $permiso = $venta ?
      PermissionSeeder::A_MODIFICAR_PRECIO . ' ' . PermissionSeeder::R_VENTA :
      PermissionSeeder::A_MODIFICAR_PRECIO . ' ' . PermissionSeeder::R_PREVENTA;
    return $this->hasPermissionTo($permiso);
  }

  public function boletasSeriesByEmpresa()
  {
    return $this->boletasSeries()
      ->where('empcodi', empcodi());
  }

  /**
   * Devoler las listas de precio del local donde se encuentra el usuario actualmente
   * 
   * @return array
   */
  public function listasCode()
  {
    return get_empresa()
      ->listas->where('LocCodi', user_()->LocalPrincipal()->LocCodi)
      ->pluck('LisCodi')
      ->all();
  }

  /**
   * Número de almacen dependiendo deacuerdo al local donde esta el usuario
   * 
   * @return int
   */
  public function numAlmacenUsed()
  {
    return substr(user_()->localPrincipal()->LocCodi, -1);
  }

  public function savePhone($phone)
  {
    return $this->update(['usutele' => $phone]);
  }

  public function hasPhoneNumber()
  {
    return (bool) $this->usutele;
  }

  public function phoneNumber()
  {
    return $this->usutele;
  }

  public function setVerificate()
  {
    $this->verificate = 1;
    $this->verificate_code = '';
    $this->save();
  }

  /**
   * Verificar si la contraseña suministrada es igual a la del usuario
   *
   * @param string $password
   * @return boolean
   */
  public function isCorrectPassword($password): bool
  {
    return $this->usucla2 == $password;
  }

  public function isCorrectVerificationCode($code)
  {
    return $this->verificate_code == $code;
  }

  public function sendActivationCode()
  {
    if (is_production()) {
      $messager = new SendMessageNexmo($this);
      return $messager->send();
    }
  }

  public function getVerificationCode()
  {
    return $this->verificate_code;
  }

  public function generateVerificationCode()
  {
    return agregar_ceros(random_int(0, 9999), 4, 0);
  }

  public function generateAndSaveVerificactionCode()
  {
    $this->verificate_code = $this->generateVerificationCode();
    $this->save();
  }

  public function getPhoneFormat()
  {
    return '51' . $this->usutele;
  }

  public function hasEmpresa(): bool
  {
    return (bool) $this->empresas->count();
  }

  public function saveDataSol($request)
  {
    $data = consultar_ruc($request->ruc);

    $razon_social = '';
    $direccion = '';

    if ($data['success']) {
      $razon_social = $data['razon_social'];
      $direccion = $data['direccion'];
    }
    $dataSol = json_encode([
      'ruc' => $request->ruc,
      'usuario_sol' => $request->usuario_sol,
      'clave_sol' => $request->clave_sol,
      'razon_social' => $razon_social,
      'direccion' => $direccion,
    ]);
    // saveDataSol
    $this->verificate_code = $dataSol;
    $this->save();
  }

  /**
   * Completar la información de la empresa
   *
   * @param Request $request
   * @return void
   */
  public function completeDataEmpresa($request)
  {
    $data = (array) json_decode($this->getVerificationCode());
    $data['nombre_comercial'] = $request->nombre_comercial;
    $data['email'] = $request->email;
    $data['direccion'] = $request->direccion;
    $this->verificate_code = json_encode($data);
    $this->save();
  }

  /**
   * Ver si el usuario tiene guardado los datos de su clave sol
   * 
   * @return bool;
   */
  public  function hasSolData(): bool
  {
    return (bool) $this->getVerificationCode();
  }

  /**
   * Ya se ha alcanzando el limite de envios permitidos del codigo de verificación al telefono
   */
  public function hasLimitCodeVerificationCodeExcede()
  {
    return $this->usercta >= get_setting('limite_codigo_verificacion_telefono', 2);
  }

  public function saveIntentoEnvio()
  {
    $cant = (int) $this->usercta;
    $this->update(["usercta" => $cant + 1]);
  }

  public function createDefaultSerie($empcodi, $serie, $loccodi = null)
  {
    $loccodi = $loccodi ?? Local::DEFAULT_LOCAL;
    SerieDocumento::create([
      "empcodi" => $empcodi,
      "usucodi" =>  $this->usucodi,
      "tidcodi" => $serie['tidcodi'],
      "sercodi" => $serie['sercodi'],
      "a4_plantilla_id" => $serie['a4_plantilla_id'],
      "a5_plantilla_id" => $serie['a5_plantilla_id'],
      "ticket_plantilla_id" => $serie['ticket_plantilla_id'],
      "numcodi" => '000000',
      "defecto" => $serie['defecto'],
      "loccodi" => $loccodi,
      "estado" => 1,
    ]);
  }

  /**
   * Asociar el usuario a un empresa
   *
   * @param [type] $empcodi
   * @param [array] $series
   * @example [["01","F001"],"03","B001"] $series
   * @return void
   */
  public function asociateToEmpresa(string $empcodi, bool $create_default_serie = false, array $series = [], $assignedLocal = true)
  {
    $user_empresa = UserEmpresa::createDefault($empcodi, $this->usucodi);


    if ($assignedLocal) {
      $user_empresa->assignToDefaultLocal();
    }

    // usuario_documento
    if ($create_default_serie) {
      foreach ($series as $serie) {
        $this->createDefaultSerie($empcodi, $serie);
      }
    }
  }

  public function setDefaultPermission()
  {
    $this->assignRole('ventas');
  }

  /**
   * Usuario de soporte
   * 
   * @return self
   */
  public static function getUserSoporte()
  {
    return self::getAdmin();
  }

  /**
   * Usuario de soporte
   * 
   * @return self
   */
  public function isAdministrative()
  {
    return $this->carcodi == User::CARGO_ADMNISTRATIVO;
  }

  public function setAllPermissions($permissions = null)
  {
    if (is_null($permissions)) {
      $permissionSeeder = new PermissionSeeder();
      $permissions = collect($permissionSeeder->getPermissions())->where('is_admin', false)->pluck('name');
    }
    $this->syncPermissions($permissions);
  }

  public function setDefaultLocal($loccodi = null)
  {
    $locales = $this->locales;

    if (!$locales->count()) {
      return;
    }

    if ($locales->where('defecto', '1')->count()) {
      return;
    }

    $loccodi ?
      $locales->where('loccodi', $loccodi)->first()->setDefecto() :
      $locales->first()->setDefecto();
  }

  public function setDefaultsSecundaryPermissions()
  {
    $permissions = collect((new PermissionSeeder())->getSecundaryUserPermissions())->pluck('name');
    $this->syncPermissions($permissions);
  }

  public function dataNotifications($searchUnRead = true, $take = true)
  {
    return (new NotificacionData($this, $searchUnRead, $take))->handle();
  }

  public function deleteAll()
  {
    $empcodi = empcodi();

    \DB::table('usuario_local')
      ->where('usucodi', $this->usucodi)
      ->where('empcodi', $empcodi)
      ->delete();

    \DB::table('usuario_documento')
      ->where('usucodi', $this->usucodi)
      ->where('empcodi', $empcodi)
      ->delete();

    \DB::table('usuario_empr')
      ->where('usucodi', $this->usucodi)
      ->where('empcodi', $empcodi)
      ->delete();
  }

  public function hasRegistros()
  {
    $success = false;
    $register = "";

    if ($this->ventas->count()) {
      $success = true;
      $register = 'ventas';
    } else if ($this->compras->count()) {
      $success = true;
      $register = 'compras';
    } else if ($this->guias->count()) {
      $success = true;
      $register = 'guias';
    } else if ($this->cajas->count()) {
      $success = true;
      $register = 'cajas';
    }

    return (object) [
      'success' => $success,
      'modelo' => $register
    ];
  }

  public function getPlanRegister()
  {
    return $this->{User::PLAN_REGISTER_CAMPO};
  }

  public function registerPermissions($permissions)
  {
    (new RegisterPermission($this,$permissions))->handle();
  }

  public function registerLocales($locales, $update = false)
  {
    (new RegisterLocales($this, $locales, $update))->handle();
  }

}
