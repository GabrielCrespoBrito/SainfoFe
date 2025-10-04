<?php

namespace App;

use App\Cotizacion;
use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Util\ConsultAgenteRetencion\ConsultAgenteRetencionMigo;

class ClienteProveedor extends Model
{
  use UsesTenantConnection;

  protected $table = "prov_clientes";
  protected $primaryKey = "PCCodi";
  public $incrementing = false;
  public $keyType = "string";
  const CREATED_AT = "User_FCrea";
  const UPDATED_AT = "User_FModi";
  const TIPO_CLIENTE = "C";
  const TIPO_PROVEEDOR = "P";
  const EMPRESA_CAMPO = "EmpCodi";
  const DEFAULT_CODIGO_ALMACEN = "00000";
  const DEFAULT_CODIGO = "00001";
  public $fillable = [
    "PCNomb",
    'PCCodi',
    'TipCodi',
    'TDocCodi',
    'PCRucc',
    'PCNomb',
    'PCDire',
    'PCDist',
    'Ent_CCI',
    'Ent_CUSP',
    'Ent_cEstadoEntidad',
    'UDelete',
    'PCDocu',
    'PCTel1',
    'ZonCodi',
    'VenCodi',
    'EmpCodi'
  ];

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('empresa', function ($query) {
      $empcodiField = self::EMPRESA_CAMPO ?? 'empcodi';
      return $query->where($empcodiField, empcodi());
    });

    static::addGlobalScope('noEliminados', function ($query) {
      return $query->where('UDelete', '!=', '*');
    });
  }

  public function isUse()
  {
    $tipDoc = $this->TipCodi;
    $isUse = false;

    $name = "undefined";

    // Cliente
    if ($tipDoc == self::TIPO_CLIENTE) {
      if ($this->ventas->count()) {
        $isUse = true;
        $name = 'Ventas';
      }
    }

    // Proveedor
    else {
      if ($this->compras->count()) {
        $isUse = true;
        $name = 'Compras';
      }
    }

    // General
    if ($this->cotizaciones->count()) {
      $isUse = true;
      $name = 'Cotizaciones';
    }

    if ($this->guias->count()) {
      $isUse = true;
      $name = 'Guias';
    }

    if (ContrataEntidad::where('entidad_id', $this->PCRucc)->count()) {
      $isUse = true;
      $name = 'Contrato';
    }

    return (object) [
      'message' => $name,
      'success' => $isUse,
    ];
  }


  public function toggleSoftDelete()
  {
    $this->UDelete = $this->UDelete == "*" ? "" : "*";
    $this->save();
  }

  public function isSoftDeleted()
  {
    return $this->UDelete == "*";
  }



  /**
   * El cliente por defecto
   * 
   * @return $this
   */
  public static function clienteDefault()
  {
    return self::findCliente(self::DEFAULT_CODIGO);
  }

  public function updateUbigeo($ubigeo)
  {
    $this->update(['PCDist' => $ubigeo]);
  }

  public function ubigeo()
  {
    return  $this->belongsTo(Ubigeo::class, 'PCDist', 'ubicodi');
  }

  public static function findProveedor($id)
  {
    return self::findByTipo($id, self::TIPO_PROVEEDOR);
  }

  public static function findCliente($id)
  {
    return self::findByTipo($id, self::TIPO_CLIENTE);
  }

  public function getId()
  {
    return $this->PCCodi;
  }

  public static function findByTipo($id, $tipo)
  {
    return self::where('PCCodi', $id)
      ->where('TipCodi', $tipo)
      ->first();
  }

  /**
   * Actualizar direccion y ubigeo si no tiene esos registros
   *
   * @param string $ubigeo
   * @param string $direccion
   * @return void
   */
  public function updateDireccionCliente($ubigeo, $direccion)
  {
    if (!$this->PCDire) {
      $this->PCDire = $direccion;
    }

    if (!$this->PCDist) {
      $this->PCDist = $ubigeo;
    }

    if ($this->isDirty(['PCDire', 'PCDist'])) {
      $this->save();
    }
  }


  public function ventas()
  {
    return $this->hasMany(Venta::class, 'PCCodi', 'PCCodi');
  }

  public function compras()
  {
    return $this->hasMany(Compra::class, 'PCcodi', 'PCCodi');
  }

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'PCCodi', 'PCCodi')
      ->where('TippCodi', $this->TipCodi);
  }

  public function setPCRuccAttribute($value)
  {
    $this->attributes['PCRucc'] = $value == '' ? '.' : $value;
  }

  public function setPCNombAttribute($value)
  {
    $nombre = strtoupper(str_replace('"', '', $value));
    $this->attributes['PCNomb'] = $nombre;
  }

  public function setPCDireAttribute($value)
  {
    $nombre = strtoupper($value);
    $this->attributes['PCDire'] = $nombre;
  }

  public function cotizaciones()
  {
    return $this->hasMany(Cotizacion::class, 'PcCodi', 'PCCodi');
  }

  public function getDescripcionAttribute()
  {
    return  "{$this->PCRucc} - {$this->PCNomb}";
  }


  public function image()
  {
    return $this->morphOne('App\ContrataEntidad', 'contratable');
  }

  public function updateDireccion($direccion)
  {
    $this->PCDist = $direccion;
    $this->save();
  }

  public static function ultimoCodigo($tipo = "C")
  {
    return agregar_ceros(
      self::where('TipCodi', $tipo)->where('EmpCodi', get_empresa('id'))->max('PCCodi'),
      5
    );
  }

  public function tipo_documento()
  {
    return $this->belongsTo(TipoDocumento::class, 'TDocCodi', 'TDocCodi');
  }

  /**
   * 
   * 
   */
  public function getNombreTipoDocumento()
  {
    return TipoDocumento::getNombreLectura($this->TDocCodi);
  }


  // Relaciones

  public function tipo()
  {
    return $this->belongsTo(TipoCliente::class, 'TipCodi', 'TippCodi');
  }

  public static function allCliente()
  {
    return self::all()->where('TipCodi', 'C')->all();
  }

  public function isCliente()
  {
    return $this->TipCodi === self::TIPO_CLIENTE;
  }

  public function isProveedor()
  {
    return $this->TipCodi === self::TIPO_PROVEEDOR;
  }

  public static function findByRuc($ruc, $empcodi = null, $tipo_cliente = self::TIPO_CLIENTE)
  {
    return self::where('PCRucc', $ruc)
      ->where('TipCodi', $tipo_cliente)
      ->first();
  }

  public static function tipoCliente()
  {
    return self::where('TipCodi', self::TIPO_CLIENTE)->where('EmpCodi', get_empresa('empcodi'));
  }



  public function agregate_cero($numero = false, $set = 0)
  {
    $numero = $numero ? $numero : "00000";
    $cero_agregar = [null, "0000", "000", "00", "0"];
    $codigoNum = ((int) $numero) + $set;
    $codigoLen = strlen((string) $codigoNum);

    return $codigoLen < 5 ? ($cero_agregar[$codigoLen] . $codigoNum) : ($numero + $set);
  }

  public static function ponerPassword()
  {
    $clientes = self::all();

    foreach ($clientes as $cliente) {

      $cliente_array = $cliente->toArray();

      \DB::connection('tenant')->table('prov_clientes')
        ->where('PCCodi', $cliente_array["PCCodi"])
        ->where('TipCodi', $cliente_array["TipCodi"])
        ->update(['PCDocu' => $cliente_array["PCRucc"]]);
    }
  }

  public function getPCCodiAttribute()
  {
    return $this->agregate_cero($this->attributes["PCCodi"]);
  }

  /**
   * Tipo de documento 
   *
   * @return int|string
   */
  public function getTipoDocumento()
  {
    return $this->TDocCodi;
  }

  /**
   * Documento
   *
   * @return string
   */
  public function getDocumento()
  {
    return $this->PCRucc;
  }

  /**
   * Nombre o Razon social
   *
   * @return string
   */
  public function getNombre($sanitize = true)
  {
    return $sanitize ? xmlHelper()->sanitize($this->PCNomb) : $this->PCNomb;
  }


  public function buscar_ultimo_codigo($tipo)
  {
    return self::ultimoCodigo($tipo);
  }

  public function setPCCodiAttribute($value)
  {
    $code = $value;

    if (isset($this->attributes['TipCodi'])) {
      $tipo = $this->attributes['TipCodi'];
      $code = $this->buscar_ultimo_codigo($tipo);
    }

    $this->attributes['PCCodi'] = $code;
  }

  public function tipo_documento_c()
  {
    return $this->belongsTo(TipoDocumento::class, 'TDocCodi', 'TDocCodi');
  }

  public function show_cliente_exacto($codigo, $tipo)
  {
    return ClienteProveedor::with('tipo_documento_c')->where('PCCodi', $codigo)->where('TipCodi', $tipo)->where('EmpCodi', empcodi())->first();
  }

  public function show_cliente($codigo)
  {
    return $this->show_cliente_exacto($codigo, self::TIPO_CLIENTE);
  }

  public function deudas()
  {
    $deudas = ['data' => []];
    foreach ($this->compras as $compra) {
      if ($compra->VtaSald > 0) {
        $comp = $compra->toArray();
        $comp['moneda'] = $compra->moneda->monabre;
        $comp['link'] = ['text' => $compra->VtaNume, 'src' => route('ventas.show', $compra->VtaOper)];
        array_push($deudas["data"], $comp);
      }
    }

    if (count($deudas['data'])) {
      $deudas['cliente'] = $this;
      $deudas['linea_credito'] = 0.00;
      $deudas['estado_linea'] = "cerrado";
      $deudas['deuda_s'] = collect($deudas['data'])->sum('VtaSald');
      $deudas['deuda_d'] = collect($deudas['data'])->sum('VtaSald');
    }
    return $deudas;
  }


  public function getTipoDocumentoCodeAttribute()
  {
    return $this->TDocCodi;
  }

  public function getDocumentoAttribute()
  {
    return $this->PCRucc;
  }

  public function getDocumentoValidAttribute()
  {
    return $this->documento ? $this->documento : '.';
  }

  /**
   * Crear cliente y proveedor por defecto
   *
   * @param string $empcodi
   * @return void
   */
  public static function createDefaults($empcodi)
  {
    self::createDefault($empcodi, self::TIPO_CLIENTE);
    self::createDefault($empcodi, self::TIPO_PROVEEDOR);
  }

  /**
   * Crear cliente o proveedor por defecto
   *
   * @param string $empcodi
   * @param string $type
   * @return void
   */
  public static function createDefault($empcodi, $type, $name = null, $codigo = null)
  {
    $name = is_null($name) ? ($type == self::TIPO_CLIENTE ? "CLIENTES VARIOS" : "PROVEEDORES VARIOS") : $name;
    $codigo = is_null($codigo) ? self::ultimoCodigo($type) : $codigo;

    $data = [];
    $data['PCCodi'] = $codigo;
    $data['TipCodi'] = $type;
    $data['PCNomb'] = $name;
    $data['PCRucc'] = ".";
    $data['UDelete'] = '';
    $data['TdoCodi'] = 0;
    $data['TDocCodi'] = 0;
    $data['LisCodi'] = "10";
    $data['MonCodi'] = "10";
    $data['VenCodi'] = "1OFIC0";
    $data['EmpCodi'] = $empcodi;
    self::create($data);
  }

  public function isRuc()
  {
    return $this->TDocCodi == TipoDocumento::RUC;
  }

  public function isRucOrDni()
  {
    return $this->isRuc() || $this->isDni();
  }


  public function isDni()
  {
    return $this->TDocCodi == TipoDocumento::DNI;
  }

  public function isOtros()
  {
    return $this->TDocCodi == TipoDocumento::NINGUNA;
  }

  public function isValidToEmitDoc($tipo_documento)
  {
    switch ($tipo_documento) {
      case TipoDocumentoPago::FACTURA:
        return $this->isRuc();
        break;

      case TipoDocumentoPago::BOLETA:
        return $this->isDni() || $this->isOtros();
        break;
    }
  }

  public function nombreDocumento() {}


  public function canEditDoc()
  {
    if ($this->ventas->count()) {
      return false;
    }

    if ($this->compras->count()) {
      return false;
    }

    if ($this->cotizaciones->count()) {
      return false;
    }

    if ($this->guias->count()) {
      return false;
    }

    return true;
  }

  public function isDefaultUser()
  {
    return
      $this->PCCodi === self::DEFAULT_CODIGO_ALMACEN ||
      $this->PCCodi === self::DEFAULT_CODIGO;
  }


  public static function findOrCreateByRuc($documento_cliente, $nombre_cliente = '', $tipo_documento = '', $empcodi,  $user)
  {
    $cliente = self::where('TdoCodi',  $tipo_documento)
      ->where('TipCodi', self::TIPO_CLIENTE)
      ->where('PCRucc', $documento_cliente)
      ->first();

    if ($cliente) {
      return $cliente->PCCodi == "00000" ? ClienteProveedor::find("00001")  : $cliente;
    }

    $userLogin = is_string($user) ? $user :optional($user)->usulogi;

    $clienteProveedor = new self();
    $clienteProveedor->EmpCodi = $empcodi;
    $clienteProveedor->TipCodi = self::TIPO_CLIENTE;
    $clienteProveedor->TDocCodi = $tipo_documento;
    $clienteProveedor->PCNomb  = $nombre_cliente;
    $clienteProveedor->PCCodi  = null;
    $clienteProveedor->UDelete  = '';
    $clienteProveedor->PCDire  = '';
    $clienteProveedor->PCDist  = '';
    $clienteProveedor->PCTel1  = '';

    $clienteProveedor->PCTel2  = '';
    $clienteProveedor->PCMail  = '';
    $clienteProveedor->PCCont  = '';
    $clienteProveedor->PCCMail = null;
    $clienteProveedor->VenCodi = '';
    $clienteProveedor->ZonCodi = "0100";
    $clienteProveedor->TdoCodi = $tipo_documento;
    $clienteProveedor->MonCodi = '01';
    $clienteProveedor->PCAfPe = 0;
    $clienteProveedor->LisCodi = "10";
    $clienteProveedor->PCLine = "0";
    $clienteProveedor->PCDeud = null;
    $clienteProveedor->PCANom = '';
    $clienteProveedor->PCARuc = '';
    $clienteProveedor->PCADir = '';
    $clienteProveedor->PCATel = '';
    $clienteProveedor->PCAEma = '';
    $clienteProveedor->User_crea = $userLogin;
    $clienteProveedor->User_ECrea = gethostname();
    $clienteProveedor->UDelete = "";
    $clienteProveedor->PCRucc  = $documento_cliente;
    $clienteProveedor->save();
    return $clienteProveedor;
  }

  public static function createCliente($data = [])
  {
    $user = auth()->user();

    $clienteProveedor = new self();
    $clienteProveedor->EmpCodi = empcodi();
    $clienteProveedor->TipCodi = self::TIPO_CLIENTE;
    $clienteProveedor->TDocCodi = $data['tipo_documento'];
    $clienteProveedor->PCNomb  = $data['nombre_cliente'];
    $clienteProveedor->PCRucc  = $data['documento'];
    $clienteProveedor->PCCodi  = null;
    $clienteProveedor->UDelete  = '';
    $clienteProveedor->PCDire  = $data['direccion'];
    $clienteProveedor->PCDist  = $data['ubigeo'];;
    $clienteProveedor->PCTel1  = $data['telefono'];
    $clienteProveedor->PCTel2  = null;
    $clienteProveedor->PCMail  = $data['email'];;
    $clienteProveedor->PCCont  = '';
    $clienteProveedor->VenCodi = '';
    $clienteProveedor->ZonCodi = "0100";
    $clienteProveedor->TdoCodi = $data['tipo_documento'];
    $clienteProveedor->MonCodi = '01';
    $clienteProveedor->PCAfPe = 0;
    $clienteProveedor->LisCodi = "10";
    $clienteProveedor->PCLine = "0";
    $clienteProveedor->PCDeud = null;
    $clienteProveedor->PCANom = '';
    $clienteProveedor->PCARuc = '';
    $clienteProveedor->PCADir = '';
    $clienteProveedor->PCATel = '';
    $clienteProveedor->PCAEma = '';
    $clienteProveedor->User_crea = $user->usulogi;
    $clienteProveedor->User_ECrea = gethostname();
    $clienteProveedor->UDelete = "";
    $clienteProveedor->save();
    return $clienteProveedor;
  }



  public function updateAgenteRetencion()
  {
    $agenteRetencion = false;
    $agenteRetencionResolucion = '';
    $agenteRetencionAPartirDel = '';

    // Consultar agente de retencion
    $consultAgenteRetencion = new ConsultAgenteRetencionMigo();
    $responseAgenteRetencion = $consultAgenteRetencion->consult($this->getDocumento());

    if ($responseAgenteRetencion['success']) {
      $agenteRetencion = true;
      $agenteRetencionResolucion = $responseAgenteRetencion['data']['resolucion'];
      $agenteRetencionAPartirDel = $responseAgenteRetencion['data']['a_partir_del'];
    } else {
      $agenteRetencion = false;
    }

    $this->update([
      'Ent_CCI' => $agenteRetencionResolucion,
      'Ent_CUSP' => $agenteRetencionAPartirDel,
      'Ent_cEstadoEntidad' => $agenteRetencion ? 1 : 0,
    ]);
  }

}
