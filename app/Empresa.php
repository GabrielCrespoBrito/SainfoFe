<?php

namespace App;

use Image;
use Exception;
use App\TipoDocumento;
use App\SerieDocumento;
use Illuminate\Http\Request;
use App\Empresa\EmpresaMethod;
use App\Jobs\Empresa\resetData;
use Hyn\Tenancy\Models\Website;
use App\Jobs\Empresa\deleteData;
use App\Models\Suscripcion\Plan;
use Hyn\Tenancy\Models\Hostname;
use App\Jobs\Empresa\MigrateInfo;
use App\Jobs\Empresa\CreatePlanes;
use App\Jobs\Empresa\ResourceLocal;
use App\Jobs\Empresa\UpdateModulos;
use App\Models\MedioPago\MedioPago;
use App\Models\UserLocal\UserLocal;
use App\Presenter\EmpresaPresenter;
use App\Jobs\Empresa\sendPendientes;
use App\Jobs\SubirCertificadoPrueba;
use App\Jobs\Empresa\saveDefaultInfo;
use App\Models\Suscripcion\OrdenPago;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Empresa\GetSeriesDefecto;
use App\Jobs\Empresa\UpdateValorVenta;
use Illuminate\Support\Facades\Schema;
use App\Jobs\Empresa\DeleteAllInfoUser;
use App\Models\Suscripcion\Suscripcion;
use App\Util\PDFGenerator\PDFGenerator;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Empresa\CambiarCampoCostos;
use App\Jobs\Empresa\UpdateCostosReales;
use App\Models\Suscripcion\PlanDuracion;
use App\Repositories\TipoPagoRepository;
use App\Util\Sunat\Request\ResolverWsld;
use App\Jobs\Empresa\sendGuiasPendientes;
use App\Jobs\MedioPago\SyncWithMainTable;
use App\Empresa\Traits\InteractWithPrinter;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use App\Models\TomaInventario\TomaInventario;
use App\Models\Empresa\Traits\SuscripcionInteract;

class Empresa extends Model
{
  public $present;

  public function __construct()
  {
    $this->present = new EmpresaPresenter($this);
  }
  
  use
    UsesSystemConnection,
    EmpresaMethod,
    InteractWithPrinter,
    SuscripcionInteract;

  protected $table = 'opciones';
  protected $primaryKey = 'id';
  const LOCAL_PRINCIPAL = "001";
  const PROVEEDOR_SUNAT = 1;
  const PROVEEDOR_NUBE = 2;
  const PRODUCCION = 1;
  const DESARROLLO = 2;
  const CONFIG_READY = 1;

  const CAMPO_NOMBRE_IMPRESORA = "FE_RPTA";
  const CAMPO_IMPRESION_DIRECTA = "FE_ENVIO";
  const CAMPO_NUMERO_COPIAS = "EmpReten";
  const CAMPO_PLAN_REGISTRO = "EmpPReten";
  const CAMPO_TIPO_CAJA = "EmpPPerc";
  // 
  const TIPO_CAJA_USUARIO = "0";
  const TIPO_CAJA_LOCAL = "1";

  const LOGO_PRINCIPAL = 1;
  const LOGO_TICKET = 2;
  const LOGO_SUBTITULO = 3;
  const LOGO_MARCA_AGUA = 4;

  // Formatos de hoja a4
  const FORMATO_A4_NORMAL = 1;
  const FORMATO_A4_IMG_ADICIONAL = 2;
  const FORMATO_A4_3COLUMN = 3;

  const FORMATO_HOJA_A4 = 0;
  const FORMATO_HOJA_A5 = 1;
  const FORMATO_HOJA_TICKET = 2;

  const PLAN_DEMO = "prueba";
  const PLAN_REGULAR = "regular";
  const PLAN_SINPLAN = null;

  public $timestamps = false;
  public $fillable = [
    "id",
    "empcodi",
    "EmpNomb",
    "EmpNomb",
    "EmpLin1",
    "EmpLin2",
    "EmpLin3",
    "EmpLin4",
    "EmpLin5",
    "EmpLin6",
    "FE_DEPA",
    "FE_PROV",
    "FE_DIST",
    "FE_UBIGEO",
    "OPC",
    "FE_CERT",
    "FE_CLAVE",
    'FE_REPO',
    'EmpLogo',
    "FE_USUNAT",
    'EmpPPerc',
    "FE_UCLAVE",
    "fe_formato",
    "FE_TIPO",
    "tipo_plan",
    "PrecIIGV",
    "fe_version",
    "need_config",
    "active",
    "fe_consulta",
    "end_plan",
  ];



  public function getImgBigAttribute()
  {
    return 'logo.png';
  }

  public function getFormato()
  {
    return [
      Empresa::FORMATO_HOJA_A4 => PDFPlantilla::FORMATO_A4,
      Empresa::FORMATO_HOJA_A5 => PDFPlantilla::FORMATO_A4,
      Empresa::FORMATO_HOJA_TICKET => PDFPlantilla::FORMATO_A4
    ][$this->fe_formato];
  }


  public function getDataAditional($field = null)
  {
    // LogEmpr
    $data_str = get_option('LogEmpr', false, $this->id());
    $data  = $data_str ? json_decode($data_str) : null;
    return $field ?  optional($data)->{$field} : $data;
  }

  public function hasImgFooter()
  {
    return (bool) $this->getDataAditional('footer_img');
  }

  public function hasTiendaApiCredentials()
  {
    return (bool) $this->getDataAditional('woocomerce_api_url');
  }


  public function hasImpresionIGV()
  {
    return (bool) $this->getDataAditional('proforma_igv');
  }

  public function getImgFooterAttribute()
  {
    return $this->getDataAditional('footer_img');
  }

  public function getNameImgLogoTicket()
  {
    return $this->ruc() .  'ticket' . '.jpg';
  }
  
  public function getIgvPorc()
  {
    $igv = get_option('Logigv');
    return (object) [
      'igvPorc' => (float) $igv,
      'igvBaseCero' => (float) math()->porcFactor($igv),
      'igvBaseUno' => math()->baseUno($igv),
    ];
  }


  public function nombreFull()
  {
    return $this->empcodi . " | " . $this->nombreRuc();
  }

  public function getDataConexionTienda()
  {
    return json_decode($this->opcion->getDataConexionTienda());
  }

  public function nombreRuc()
  {
    return $this->EmpNomb . "-" . $this->EmpLin1;
  }

  public static function findByRuc($ruc)
  {
    return self::where('EmpLin1', $ruc)->first();
  }

  /**
   * Tipo de documento de la empresa siempre sera ruc
   *
   * @return int|string
   */
  public function getTipoDocumento()
  {
    return TipoDocumento::RUC;
  }

  /**
   * Documento de la empresa
   *
   * @return string
   */
  public function getDocumento()
  {
    return $this->EmpLin1;
  }

  /**
   * Nombre o Razon social de la empresa
   *
   * @return string
   */
  public function getNombre()
  {
    return $this->EmpNomb;
  }

  public function ruc()
  {
    return $this->EmpLin1;
  }


  public function user()
  {
  }

  public function listas()
  {
    return $this->hasMany(ListaPrecio::class, 'empcodi', 'empcodi');
  }

  public function users()
  {
    return $this->hasManyThrough(
      User::class, // Tabla objetivo
      UserEmpresa::class,  // Tabla intermedia
      'empcodi',  // foreign_key UserEmpresa
      'usucodi',  // foreign_key User
      'empcodi',  // local key User
      'usucodi'   // local key User Empresa
    );
  }

  public function empresa_usuarios()
  {
    return $this->hasMany(UserEmpresa::class, 'empcodi', 'empcodi');
  }


  public function locales()
  {
    return $this->hasMany(Local::class, 'empcodi', 'empcodi');
  }

  public function user_local()
  {
    return $this->hasMany(UserLocal::class, 'empcodi', 'empcodi');
  }

  public static function findByCodi($codi)
  {
    return self::where('empcodi', $codi)->first();
  }

  public function documentosPendientes()
  {
    return $this->hasMany(NotificacionDocumentosPendientes::class, 'EmpCodi', 'empcodi');
  }

  /**
   * Table usuario_documento
   *
   * @return HasMany
   */
  public function documentos()
  {
    return $this->hasMany(SerieDocumento::class, 'empcodi', 'empcodi');
  }

  public function resetDocumentos()
  {
    foreach ($this->documentos as $documento) {
      $documento->update([
        'numcodi' => agregar_ceros(0, 6, 0),
      ]);
    }
  }

  public function periodos()
  {
    return $this->hasMany(Periodo::class, 'empcodi', 'empcodi');
  }

  public function personal()
  {
    return $this->hasMany(Personal::class, 'EmpCodi', 'empcodi');
  }

  public function clientes()
  {
    return $this->hasMany(ClienteProveedor::class, 'EmpCodi', 'empcodi')->where('TipCodi', 'C');
  }

  public function proveedores()
  {
    return $this->hasMany(ClienteProveedor::class, 'EmpCodi', 'empcodi')->where('TipCodi', 'P');
  }

  public function ventas()
  {
    return $this->hasMany(Venta::class, 'EmpCodi', 'empcodi');
  }

  public function guias()
  {
    return $this->hasMany(GuiaSalida::class, 'EmpCodi', 'empcodi');
  }

  public function empresasTransporte()
  {
    return $this->hasMany(EmpresaTransporte::class, 'empresa_id', 'empcodi');
  }

  public function transportistas()
  {
    return $this->hasMany(Transportista::class, 'EmpCodi', 'empcodi');
  }
  public function vehiculos()
  {
    return $this->hasMany(Vehiculo::class, 'empcodi', 'empcodi');
  }
  public function resumenes()
  {
    return $this->hasMany(Resumen::class, 'EmpCodi', 'empcodi');
  }

  public function bancos()
  {
    return $this->hasMany(BancoEmpresa::class, 'EmpCodi', 'empcodi');
  }

  public function marcas()
  {
    return $this->hasMany(Marca::class, 'empcodi', 'empcodi');
  }
  public function grupos()
  {
    return $this->hasMany(Grupo::class, 'empcodi', 'empcodi');
  }
  public function familias()
  {
    return $this->hasMany(Familia::class, 'empcodi', 'empcodi');
  }

  public function cajas()
  {
    return $this->hasMany(Caja::class, 'EmpCodi', 'empcodi');
  }

  public function cajas_aperturadas($caja_exclude = null)
  {
    if ($caja_exclude) {
      return $this->cajas
        ->where('CajNume', "!=", $caja_exclude)
        ->where('CajEsta', Caja::ESTADO_APERTURADA)
        ->where('CueCodi', Caja::TIPOCAJA)
        ->all();
    }

    return $this->cajas
      ->where('CajEsta', Caja::ESTADO_APERTURADA)
      ->where('CueCodi', Caja::TIPOCAJA)
      ->all();
  }

  public function getOpcion($opcion)
  {
    switch ($opcion) {
      case 'boleta_limite':
        return $this->opcion->EmpCodOS;
        break;
      case 'igv':
        return $this->opcion->Logigv;
        break;
      default:
        break;
    }
  }

  /**
   * Porcentaje de igv del sistema
   * 
   * 
   */
  public function getigvPorcentajeAttribute()
  {
    return $this->opcion->Logigv;
  }

  public function ultima_caja()
  {
    if ($this->cajas->count()) {
      return $this->cajas->max('CajNume');
    }

    return "";
  }

  public function hasOption()
  {
    return !is_null($this->opcion);
  }

  public function almacenes()
  {
    return $this->hasMany(Almacen::class, 'EmpCodi', 'empcodi');
  }

  public function almacen_princial()
  {
    return $this->almacenes->where('LocNomb', 'PRINCIPAL')->first();
  }

  public function almacene_default()
  {
    return $this->almacenes->where('', Caja::LOCAL_DEFAULT);
  }

  public function almacenes_elegibles()
  {
    return $this->almacenes->where('LocCodi', '!=', Almacen::LOCAL_INAVAILABLE);
  }


  public function opcion()
  {
    return $this->hasOne(EmpresaOpcion::class, 'EmpCodi', 'empcodi');
  }
  public function isA41()
  {
    return $this->fe_formato == 0;
  }

  public function isA4()
  {
    return $this->fe_formato == self::FORMATO_HOJA_A4;
  }

  public function isA5()
  {
    return $this->fe_formato == self::FORMATO_HOJA_A5;
  }

  public function isTicket()
  {
    return $this->fe_formato == self::FORMATO_HOJA_TICKET;
  }

  public function isA42()
  {
    return $this->fe_formato == 1;
  }


  public function isA412()
  {
    return $this->fe_formato == 3;
  }

  public function getLogo($formato)
  {
    $formato =  $formato != Venta::FORMATO_TICKET ? 1 : 2;

    return $this->logoEncode($formato);
  }

  public function envioFactura()
  {
    return $this->fe_envfact;
  }
  public function envioBoleta()
  {
    return $this->fe_envbole;
  }
  public function envioNotaCredito()
  {
    return $this->fe_envncre;
  }
  public function envioNotaDebito()
  {
    return $this->fe_envndebi;
  }

  public function hasLogoPrincipal()
  {
    return $this->hasLogo(self::LOGO_PRINCIPAL);
  }

  public function hasLogoSecundario()
  {
    return $this->hasLogo(2);
  }

  public function hasLogoSubtitulo()
  {
    return $this->hasLogo(self::LOGO_SUBTITULO);
  }

  public function hasLogoTicket()
  {
    return $this->hasLogo(self::LOGO_TICKET);
  }

  public function hasLogoMarcaAgua()
  {
    return $this->hasLogo(self::LOGO_MARCA_AGUA);
  }

  public function hasLogo($principal = self::LOGO_PRINCIPAL)
  {
    switch ($principal) {
      case self::LOGO_PRINCIPAL:
        return (bool) $this->EmpLogo;
        break;
      case self::LOGO_TICKET:
        return (bool) $this->EmpLogo1;
        break;
      case self::LOGO_SUBTITULO:
        return (bool) $this->EmpDWeb;
        break;
      case self::LOGO_MARCA_AGUA:
        return (bool) $this->FE_RESO;
        break;
      default:
        throw new Exception("The logo $principal don't exists", 1);
        break;
    }
  }

  public function logoEncode($principal = 1)
  {
    if ($principal == self::LOGO_PRINCIPAL) {
      $logo = $this->EmpLogo != null ? $this->EmpLogo : null;
    } elseif ($principal == self::LOGO_TICKET) {
      $logo = $this->EmpLogo1 != null ? $this->EmpLogo1 : null;
    } elseif ($principal == self::LOGO_SUBTITULO) {
      $logo = $this->EmpDWeb != null ? $this->EmpDWeb : null;
    } elseif ($principal == self::LOGO_MARCA_AGUA) {
      $logo = $this->FE_RESO != null ? $this->FE_RESO : null;
    }

    if ($logo) {
      return chunk_split(base64_encode($logo));
    }

    return null;
  }

  public function getLogoEncodePrincipal()
  {
    return $this->logoEncode(self::LOGO_PRINCIPAL);
  }

  public function getLogoEncodeTicket()
  {
    return $this->logoEncode(self::LOGO_TICKET);
  }

  public function getLogoEncodeSubtitulo()
  {
    return $this->logoEncode(self::LOGO_SUBTITULO);
  }

  public function getLogoEncodeMarcaAgua()
  {
    return $this->logoEncode(self::LOGO_MARCA_AGUA);
  }

  public function desarrollo()
  {
    return $this->FE_TIPO == 0;
  }
  public function homologacion()
  {
    return $this->FE_TIPO == 1;
  }
  public function produccion()
  {
    return $this->isAmbiente(self::PRODUCCION);
  }

  public function url()
  {
    return $this->belongsTo(OpcionUrl::class, 'FE_TIPO', 'ID');
  }

  public function setImpresionAttribute()
  {
  }


  // SoftEsta

  public function cdrUrl()
  {
    return $this->is_ose() ? OpcionUrl::find(7)->Nombre : OpcionUrl::find(3)->Nombre;
  }

  public function is_ose()
  {
    return $this->isProveedor(self::PROVEEDOR_NUBE);
  }


  public function urlBeta()
  {
    return $this->is_ose() ? OpcionUrl::find(6)->Nombre : OpcionUrl::find(0)->Nombre;
  }

  public function urlHomologacion()
  {
    return OpcionUrl::find(1)->Nombre;
  }
  public function urlGuia()
  {
    return $this->is_ose() ? OpcionUrl::find(7)->Nombre : OpcionUrl::find(4)->Nombre;
    // return OpcionUrl::find(4)->Nombre;    
  }
  public function urlGuiaBeta()
  {
    return $this->is_ose() ? OpcionUrl::find(6)->Nombre : OpcionUrl::find(5)->Nombre;
  }
  public function urlProduccion()
  {
    return $this->is_ose() ? OpcionUrl::find(7)->Nombre : OpcionUrl::find(2)->Nombre;
  }
  public function urlSent($is_guia = false)
  {
    // guias
    if ($is_guia) {
      return $this->produccion() ?
        $this->urlGuia() :
        $this->urlGuiaBeta();
    }

    // facturas / boletas / nota debito / nota credito
    if ($this->produccion()) {

      // return $this->urlProduccion();
      return $this->isOse() ? $this->cdrUrl() : getXmlProduccion();
      // return $this->isOse() ? $this->cdrUrl() : getXmlProduccion();
      // return $this->isOse() ? $this->cdrUrl() : "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
    } else {
      return $this->urlBeta();
    }
  }

  public function nombre()
  {
    return $this->EmpNomb;
  }

  public static function find($codi)
  {
    return self::where('empcodi', $codi)->first();
  }

  public function id()
  {
    return $this->empcodi;
  }

  public function isXml_2_1()
  {
    return $this->fe_version === "2.1";
  }

  public static function createTestEmpresa()
  {
    $nombre = str_random(16);
    $empresa = new self;
    $empresa->empcodi = agregar_ceros(self::max('empcodi'), 3);
    $empresa->EmpNomb = $nombre;
    $empresa->EmpLin1 = random_int(10000000, 25000000);
    $empresa->EmpLin5 = $nombre;
    $empresa->save();
    return $empresa;;
  }

  public function setUbigeo($ubigeo_id)
  {
    $ubigeo = Ubigeo::find($ubigeo_id);
    // _dd( $ubigeo );
    // exit();
    if ($ubigeo) {
      $this->FE_DEPA = $ubigeo->departamento->depnomb;
      $this->FE_PROV = $ubigeo->provincia->provnomb;
      $this->FE_DIST = $ubigeo->ubinomb;
      $this->FE_UBIGEO = $ubigeo_id;
    }
  }


  public static function saveData($data, $empresa = null, $is_new = false, $onlyDataPrincipal = false)
  {
    if ($is_new) {
      $empresa = new Empresa;
      $empresa->empcodi = agregar_ceros(Empresa::max('empcodi'), 3);
      $empresa->active =  $data['active'] ?? 0;
    }

    if (!$onlyDataPrincipal) {
      $empresa->EmpNomb = $data['nombre_empresa'];
      $empresa->EmpLin1 = $data['ruc'];
    }

    $empresa->EmpLin2 = $data['direccion'];
    $empresa->EmpLin3 = $data['email'];
    $empresa->EmpLin4 = $data['telefonos'];
    $empresa->EmpLin5 = $data['nombre_comercial'];
    $empresa->EmpLin6 = $data['rubro'];
    $empresa->EmpPReten = $data['EmpPReten'] ?? null;

    $empresa->setUbigeo($data['ubigeo']);
    if (!$onlyDataPrincipal) {
      // facturacion
      $empresa->OPC  = (int) isset($data['facturacion']);
      $empresa->FE_CERT = $data['ruc'];
      $empresa->FE_CLAVE = $data['clave_firma'];
      $empresa->FE_USUNAT   = $data['usuario_sol'];
      $empresa->FE_UCLAVE   = $data['clave_sol'];

      $empresa->fe_envfact  = (int) isset($data['fe_envfact']);
      $empresa->fe_envbole  = (int) isset($data['fe_envbole']);
      $empresa->fe_envncre  = (int) isset($data['fe_envncre']);
      $empresa->fe_envndebi = (int) isset($data['fe_envndebi']);

      $empresa->FE_DATA = $data['formato_a4'] ?? Empresa::FORMATO_A4_NORMAL;

      $empresa->fe_servicio = $data['fe_servicio'];
      $empresa->fe_ambiente = $data['fe_ambiente'];
      $empresa->fe_formato =  $data['formato_hoja'];
      // $empresa->FE_TIPO = $data['tipo_envio_servicio'];
      $empresa->fe_version =  $data['version_xml'];
      $empresa->PrecIIGV = (int) isset($data['precio_igv']);
      $empresa->fe_consulta = config('app.url_busqueda_documentos');
    }

    // Imprimir
    $empresa->FE_ENVIO  = (int) isset($data['imprimir']);
    // Nombre de la impresora 
    $empresa->FE_RPTA  = $data['nombre_impresora'] ?? '';
    $empresa->FE_REPO  = $data['FE_REPO'] ?? null;
    // Cantida de copias
    $empresa->EmpReten  = $data['cant_copias'] ?? 1;

    if (isset($data['logo_principal'])) {
      $img = Image::make($data['logo_principal']);
      $img->encode('jpeg');
      $empresa->EmpLogo = $img;
    }

    if (isset($data['logo_secundario'])) {
      $img = Image::make($data['logo_secundario']);
      $img->encode('jpeg');
      $empresa->EmpLogo1 = $img;
    }

    if (isset($data['logo_subtitulo'])) {
      $img = Image::make($data['logo_subtitulo']);
      $img->encode('jpeg');
      $empresa->EmpDWeb = $img;
    }

    if (isset($data['logo_marca_agua'])) {
      $img = Image::make($data['logo_marca_agua']);
      $img->encode('jpeg');
      $empresa->FE_RESO = $img;
    }

    $empresa->save();

    if (!$is_new) {
      $empresa->cleanCache();
    }

    return $empresa;
  }


  public function _toArray()
  {

    $fields = func_get_args();

    if (count($fields)) {
      $arr = $this->toArray();
      foreach ($fields as $field) {
        $arr[$field] = "";
      }

      return $arr;
    }

    return $this->toArray();
  }

  public function vendedores()
  {
    return $this->hasMany(Vendedor::class, 'empcodi', 'empcodi');
  }

  public function formas_pagos()
  {
    return $this->hasMany(FormaPago::class, 'empcodi', 'empcodi');
  }

  public function migrateInfo()
  {
    MigrateInfo::dispatchNow($this);
  }


  public function motivosIngresos()
  {
    return $this->hasMany(MotivoIngreso::class, 'EmpCodi', 'empcodi');
  }

  public function motivosEgresos()
  {
    return $this->hasMany(MotivoEgreso::class, 'EmpCodi', 'empcodi');
  }

  public function unidades()
  {
    return $this->hasMany(Unidad::class, 'empcodi', 'empcodi');
  }

  /**
   * Ver si en los precio de los producto de la empresa incluye igv
   * 
   * @return bool
   */
  public function incluyeIgv(): bool
  {
    return (bool) $this->PrecIIGV;
  }

  public function localPrincipal()
  {
    return $this->locales->where('LocCodi', self::LOCAL_PRINCIPAL)->first();
  }


  /**
   * Comprobar si esta habilitado el envio de un tipo de documento especifico
   *
   * @param string $tidCodi
   * @return bool
   */
  public function isAvailableSendTidDocument(string $tidCodi): bool
  {
    $fieldsTidCodi = [
      '01' => 'fe_envfact',
      '03' => 'fe_envbole',
      '07' => 'fe_envncre',
      '08' => 'fe_envndebi',
    ];
    return (bool) $this->{$fieldsTidCodi[$tidCodi]};
  }

  /**
   * Si esta activo el envio directo de las boletas
   *
   * @return bool
   */
  public function sendDirectBoleta()
  {
    return $this->isAvailableSendTidDocument('03');
  }


  public function isOse()
  {
    return $this->is_ose();
  }

  /**
   * Obtener el proveedor
   *
   * @return string
   */
  public function getProveedor()
  {
    if ($this->isProveedor(self::PROVEEDOR_SUNAT)) {
      return ResolverWsld::SUNAT;
    }

    if ($this->isProveedor(self::PROVEEDOR_NUBE)) {
      return ResolverWsld::NUBEFACT;
    }

    throw new \Exception("Error Processing Request", 1);
  }


  /**
   * Si la  tiene un local asociado
   * @param string $local 
   * 
   * @return bool
   */
  public function hasLocal($local)
  {
    return (bool) $this->locales->where('LocCodi', $local)->count();
  }


  /**
   * Si la empresa es de un proveedor especifico
   *
   * @param string $proveedor
   * @return boolean
   */
  public function isProveedor($proveedor)
  {
    return $this->fe_servicio == $proveedor;
  }


  /**
   * Si la empresa esta en un proveedor especifico
   *
   * @param string $ambiente
   * @return boolean
   */
  public function isAmbiente($ambiente)
  {
    return $this->fe_ambiente == $ambiente;
  }

  /**
   * Si la empresa esta en ambiente de producción o desarrollo
   *
   * @return bool
   */
  public function isProduction()
  {
    return $this->isAmbiente(self::PRODUCCION);
  }

  public function getSunatData()
  {
    switch ($this->fe_servicio) {
      case '1':
        $proveedor = ResolverWsld::SUNAT;
        break;
      case '2':
        $proveedor = ResolverWsld::NUBEFACT;
        break;
    };

    // Cuando es 1 es producción, cuando sea 2 desarrollo
    $ambiente = $this->fe_ambiente == "1";

    return (object) [
      'proveedor' => $proveedor,
      'ambiente' => $ambiente,
    ];
  }

  public function getDatabase()
  {
    $fqdn = $this->ruc() . '.' . env('APP_URL_BASE', 'localhost');

    // $fqdn = $this->ruc() . '.' . env('APP_URL_BASE' , 'localhost');

    $hostname = Hostname::where('fqdn', $fqdn)->firstOrfail();

    return $hostname->website->uuid;
  }


  public function getFqdn()
  {
    return $this->ruc() . '.' . env('APP_URL_BASE', 'localhost');
  }


  /**
   * Generar el nombre de la bd para la empresa
   *
   * @example empcodi_ruc = 001_2087563254
   * @return string
   */
  public function getUUid()
  {
    return $this->empcodi . '_'  .  $this->ruc();
  }

  public function getHost()
  {
    return Hostname::where('fqdn', $this->getFqdn())->first();
  }

  public function getWebsite()
  {
    return Website::where('uuid', $this->getUUid())->first();
  }



  /**
   * Eliminar base de datos juntos a registro de hostname y  website bd
   * 
   * @return void
   */

  public function deleteDatabase()
  {
    $host = $this->getHost();

    if ($host == null) {
      return;
    }

    $databaseName = optional($host->website)->uuid;

    if ($databaseName == null) {
      return;
    }


    try {
      Schema::getConnection()->getDoctrineSchemaManager()->dropDatabase("`{$databaseName}`");
    } catch (\Throwable $th) {
    }

    $host->website->forceDelete();
    $host->forceDelete();
  }


  /**
   * Eliminar base de datos juntos a registro de hostname y  website bd
   * 
   * @return void
   */

  public function deleteForceDatabase()
  {
    $host = $this->getHost();
    $website = $this->getWebsite();
    $databaseName = $this->getUUid();

    // Eliminar base de datose en caso de que exista
    try {
      Schema::getConnection()->getDoctrineSchemaManager()->dropDatabase("`{$databaseName}`");
    } catch (\Throwable $th) {
    }

    if ($website) {
      $website->forceDelete();
    }

    if ($host) {
      $host->forceDelete();
    }
  }


  /**
   * Borrar toda la informacion asociada a una empresa en especifico
   *
   * @return void
   */
  public function deleteAllInformation()
  {
    // Borrar configuración de la empresa

    // Usuario Local
    $users_locals = UserLocal::withoutGlobalScope('empresa')->where('empcodi', $this->empcodi)->get();

    if ($users_locals->count()) {
      foreach ($users_locals as $user_local) {
        $user_local->delete();
      }
    }
    
    // usuario_empr
    $users_empresa = $this->empresa_usuarios;
    if ($users_empresa->count()) {
      foreach ($users_empresa as $user_empresa) {
        $user_empresa->delete();
      }
    }

    // usuario_documento
    $documentos = $this->documentos;
    if ($documentos->count()) {
      foreach ($documentos as $documento) {
        $documento->delete();
      }
    }

    // Borrar hostnames y website
    optional($this->opcion)->delete();
    optional($this->getWebsite())->delete();
    optional($this->getHost())->delete();

  
    $this->delete();
  }


  public function saveInformacionDefecto($create_local = false)
  {
    saveDefaultInfo::dispatchNow($this, $create_local);
  }

  public function DeleteAllInfoUser()
  {
    DeleteAllInfoUser::dispatchNow();
  }

  /**
   * Verificar si la empresa ya tiene registrada su información por defecto
   * 
   * @example tru 
   * @return bool
   */
  public function noSeHaGuardadoInformacionPorDefecto()
  {
    return $this->FE_REPO == "11";
  }

  public function guardarEstadoSeHaGuardadoInformacionPorDefecto()
  {
    $this->update(['FE_REPO' => null]);
  }

  public function guardarEstadoNoSeHaGuardadoInformacionPorDefecto()
  {
    $this->update(['FE_REPO' => "11"]);
  }


  public function updateTipoCambio($tipo_cambio)
  {
    $this->opcion->update(['FE_RPTA' => $tipo_cambio]);
    Cache::forget("option_empresa" . $this->empcodi);
    return $this;
  }

  public function getTipoCambioPublico()
  {
    return $this->opcion->FE_RPTA;
  }



  public function hasDefaultInfo()
  {
    if ($this->marcas->count()) {
      return true;
    }

    return false;
  }


  public function deleteComplete()
  {
    $this->deleteDatabase();
    optional($this->opcion)->delete();
    $this->delete();
  }

  // Eliminar suscripciones
  public function deleteSuscripciones()
  {
    foreach ($this->ordenes_pago as $orden) {

      $suscripcion = $orden->suscripcion;


      if( $suscripcion ){

        
        if( $suscripcion->usos ){
          
          foreach ($suscripcion->usos as $uso) {
            $uso->delete();
          }
        }
      }
        
      optional($suscripcion)->delete();
      optional($orden)->delete();
    }
  }

  /**
   * Eliminar toda la información que exista en base de datos principal
   *
   * @return void
   */
  public function deleteInfoInDatabasePrincipal()
  {
    // Eliminar opción
    optional($this->opcion)->delete();

    // Eliminar periodos
    foreach ($this->periodos as $periodo) {
      $periodo->delete();
    }

    $this->deleteSuscripciones();
    $this->deleteAllInformation();
  }

  public function cuentaDetraccion()
  {
    return $this->bancos->where('Detract', 1)->first();
  }

  public function numeroCuentaDetraccion()
  {
    return optional($this->cuentaDetraccion())->numero();
  }


  public function updateParametrosBasic(Request $request)
  {
    $this->update([
      'PrecIIGV' => $request->PrecIIGV,
      'fe_formato' => $request->formato_hoja,
    ]);
  }

  public function ordenes_pago()
  {
    return $this->hasMany(OrdenPago::class, 'empresa_id', 'empcodi');
  }

  public function userOwner()
  {
    return optional($this->empresa_usuarios->first())->user;
  }

  public function suscripciones()
  {
    return $this->hasMany(Suscripcion::class,  'empresa_id', 'empcodi');
  }

  public function suscripcionActual()
  {
    return Suscripcion::with('orden.planduracion')->where('actual', Suscripcion::ACTUAL)
      ->where('empresa_id', $this->id())->first();
  }

  public function cacheID()
  {
    return 'empresa' . $this->id();
  }

  public function updateTiempoSuscripcion($fecha_final)
  {
    $this->update(['end_plan' => $fecha_final]);

    Cache::forget($this->cacheID());
  }

  public function desactivarSuscripciones()
  {
    foreach ($this->suscripciones as $suscripcion) {
      $suscripcion->update(['actual' => Suscripcion::ACTUAL]);
    }
  }

  /**
   * Fecha donde termina la suscripción actual
   *
   * @return boolean
   */
  public function getEndDateSuscripcion()
  {
    return $this->end_plan;
  }

  public function direccion()
  {
    return $this->EmpLin2;
  }

  public function telefonos()
  {
    return $this->EmpLin4;
  }

  public static function getEmpresaFacturadora()
  {
    return self::find("002");
  }

  public function email()
  {
    return $this->EmpLin3;
  }

  public function sumarConsumo($caracteristica, $quantity = 1)
  {
    $this->suscripcionActual()->sumarRestarConsumo($caracteristica, $quantity, true);
  }

  public function restarConsumo($caracteristica, $quantity = 1)
  {
    $this->suscripcionActual()->sumarRestarConsumo($caracteristica, $quantity, false);
  }

  public function consumoMaximo($caracteristica)
  {
    return $this->suscripcionActual()->consumoMaximo($caracteristica);
  }

  public function excedeConsumo($caracteristica, $cantidad = 1)
  {
    return $this->suscripcionActual()->excedeConsumo($caracteristica, $cantidad);
  }

  public function resetSuscripcionCaracteristicas()
  {
    return $this->suscripcionActual()->initConsumo();
  }

  public function suscripcionDemoAssociate()
  {
    $plan_demo = Plan::getDemo();
    $duracion = $plan_demo->duraciones->first();
    $orden_pago = OrdenPago::createFromPlanDuracion($duracion, $this->id(), $this->userOwner()->id(), true);
    $orden_pago->createSuscripcion();
  }

  /**
   * Quitar el estado de actual de la/s suscripcion/es anteriores de la empresa 
   * 
   * @return bool
   */
  public function updateSuscripciones(Suscripcion $suscripcion)
  {
    $suscripciones = $this->suscripciones->where('id', '!=', $suscripcion->id);

    foreach ($suscripciones as $suscripcion) {
      $suscripcion->update([
        'actual' =>  0,
        'estatus' =>  Suscripcion::ESTATUS_VENCIDA,
      ]);
    }
  }

  public function subirCertificadoPrueba()
  {
    SubirCertificadoPrueba::dispatchNow($this);
  }

  /**
   * Si la empresa necesita la configuración final para empezar a trabajar
   *
   * @return void
   */
  public function configDataIsSave()
  {
    return $this->need_config == self::CONFIG_READY;
  }

  /**
   * Verificar que la empresa necesite guardar configuración
   *
   * @return void
   */
  public function needConfig()
  {
    return $this->need_config == self::CONFIG_READY;
  }


  public function updateNeedConfigStatus($need = 1)
  {
    $this->update([
      'need_config' => $need
    ]);

    return $this;
  }

  public function isPlanDemo()
  {
    return $this->tipo_plan == self::PLAN_DEMO;
  }

  public function isPlanRegular()
  {
    return $this->tipo_plan == self::PLAN_REGULAR;
  }


  public function getCacheKey()
  {
    return 'empresa' . $this->empcodi;
  }

  public function getCacheKeyOpcion()
  {
    return 'option_empresa' . $this->empcodi;
  }

  public function cleanCache()
  {
    cache()->forget($this->getCacheKey());
    cache()->forget($this->getCacheKeyOpcion());
  }

  /**
   * Si la empresa tiene la suscripción de demo, ponerle el estado para obligar a registrar su información para su clave sol
   *
   * @return void
   */
  public function saveRequiredConfig()
  {
    if ($this->isPlanDemo()) {
      $this->updateNeedConfigStatus();
      if (!$this->isProduction()) {
        empresa_bd_tenant($this->empcodi);
        $this->resetData();
      }
      $this->cleanCache();
    }

    return $this;
  }

  /**
   * Guardar el tipo de plan con la que esta trabajando al empresa
   *
   * @return $this
   */
  public function saveTipoPlan($tipo_plan)
  {
    $this->update([
      'tipo_plan' => $tipo_plan
    ]);
  }

  public function getProductos()
  {
    return $this->hasMany(Producto::class, 'empcodi', 'empcodi');
  }

  public function direccionFormato()
  {
    return str_replace('[nl]', '<br>', $this->EmpLin2);
  }

  public function scopeActivas($query)
  {
    return $query->where('active', 1);
  }

  public function scopeAmbienteProduccion($query)
  {
    return $query->where('fe_ambiente', self::PRODUCCION);
  }

  public function updateYear()
  {
    $lastPeriodo  = $this->periodos->sortByDesc('Pan_cAnio')->pluck('Pan_cAnio')->first();

    if ($lastPeriodo != date('Y')) {
      Periodo::createDefault($this->empcodi);
    }
  }

  public function saveTempLogo()
  {
    $img = Image::make(public_path(file_build_path('static', 'demo', 'logo.png')));
    $img->encode('jpeg');
    $this->EmpLogo = $img;
    $this->save();
    Cache::forget('empresa.' . $this->id);
  }

  public function getViewPDF()
  {
    switch ($this->FE_DATA) {
      case self::FORMATO_A4_3COLUMN:
        return 'ventas.pdf_a4_3';
        break;
      case self::FORMATO_A4_IMG_ADICIONAL;
        return 'ventas.pdf_a4_2';
        break;
      default:
        return 'ventas.pdf';
        break;
    }
  }

  public function getViewPDFCotizacion()
  {
    switch ($this->FE_DATA) {
      case self::FORMATO_A4_3COLUMN:
        return 'cotizaciones.pdf_a4_3';
        break;
      case self::FORMATO_A4_IMG_ADICIONAL;
        return 'cotizaciones.pdf_a4_2';
        break;
      default:
        return 'cotizaciones.pdf';
        break;
    }
  }


  /**
   * Si la empresa tiene habilitado el uso de la marca de agua en los documentos pdf
   * 
   * @return bool
   */
  public function availabledMaskWater()
  {
    return true;
  }

  public function isA4Normal()
  {
    return $this->FE_DATA = self::FORMATO_A4_NORMAL;
  }

  public function isA4ImgAdiconal()
  {
    return $this->FE_DATA = self::FORMATO_A4_IMG_ADICIONAL;
  }

  public function getOrdenCampos()
  {
    $rpta = $this->FE_URL;

    if ($rpta) {
      $data = json_decode($rpta);

      $precio_unitario = $data->precio_unitario;
      $valor_unitario = $data->valor_unitario;
      $descuento = $data->descuento;
      $importe = $data->importe;
    } else {
      $precio_unitario = true;
      $valor_unitario = true;
      $descuento = true;
      $importe = true;
    }

    return [
      'precio_unitario' => $precio_unitario,
      'valor_unitario' => $valor_unitario,
      'descuento' => $descuento,
      'importe' => $importe,
    ];
  }

  public function updatePreciosProductos()
  {
    $productos_chunk = $this->getProductos()->get()->chunk(50);
    foreach ($productos_chunk as $productos) {
      foreach ($productos as $producto) {
        $producto->updatePrecioByUnidadPrincipal();
      }
    }
  }


  public function updateValorVenta()
  {
    (new UpdateValorVenta)->handle();
  }

  /**
   * Cambiar costo de la empresa
   * 
   * @return bool
   * 
   */
  public function cambiarCampoCostos()
  {
    (new CambiarCampoCostos)->handle();
  }


  /**
   * Cambiar costo de la empresa
   * 
   * @return bool
   * 
   */
  public function updateCostosReales()
  {
    (new UpdateCostosReales)->handle();
  }

  /**
   * Si una empresa es activa
   */
  public function isActive()
  {
    return (bool) $this->active;
  }

  public function generatePlantillaPDF($plantilla_id)
  {
    $nameDocumento = time() . '.pdf';
    $routeTemp = file_build_path('temp', $nameDocumento);
    $plantilla = PDFPlantilla::find($plantilla_id);
    $pdf = new PDFGenerator(
      view($plantilla->vista, $plantilla->getDataPlantilla()),
      PDFGenerator::HTMLGENERATOR
    );
    $pdf->generator->setGlobalOptions(PDFGenerator::getSetting($plantilla->formato, PDFGenerator::HTMLGENERATOR));
    $success = $pdf->save($routeTemp);
    return asset("temp/{$nameDocumento}");
  }

  public function updateAnioTrabajo()
  {
    $last_periodo = $this->periodos->sortByDesc('Pan_cAnio')->first();
    $current_year = date('Y');
    if ($last_periodo->Pan_cAnio != $current_year) {
      Periodo::createDefault($last_periodo->empcodi, $current_year, true);
    }
  }

  // public function addAditionalParametro( array $newAditionalData )
  // {
  //   $currentAditionalData = $this->getDataAditional();

  //   $data = array_merge($newAditionalData ,$currentAditionalData );

  //   $this->updateDataAdicional($data);
  // }

  public function resourceLocal()
  {
    return (new ResourceLocal($this));
  }

  public function getUrlLogoTicket()
  {
    $url_logo = null;

    if ($this->hasLogo(self::LOGO_TICKET)) {

      $fh = fileHelper($this->ruc());

      $url_logo_bucket =  config('app.aws_url_bucket') . '/images/' . $this->getNameImgLogoTicket();

      if ($fh->imgExist($this->getNameImgLogoTicket())) {
        $url_logo =  $url_logo_bucket;
      } else {
        if ($this->storeLogo(self::LOGO_TICKET)) {
          $url_logo = $url_logo_bucket;
        }
      }
    }


    return $url_logo;
  }

  public function storeLogo($logo)
  {
    switch ($logo) {
      case self::LOGO_TICKET:
        $logo_content = $this->EmpLogo1;
        return fileHelper($this->ruc())->save_img($this->getNameImgLogoTicket(), $logo_content);
    }
  }

  public function getNombreFormato()
  {
    return sprintf(
      "%s | %s | %s | %s ",
      $this->id(),
      $this->isProduction() ? 'PROD' : 'DESR',
      $this->ruc(),
      $this->nombre()
    );
  }


  public static function formatList($dataSunat = false)
  {
    $empresas = Empresa::all();
    $empresa_id_selected = session()->get('empresa_id');
    $empresas_format = [];

    foreach ($empresas as $empresa) {
      $empresa_id = $empresa->id();
      $empresas_format[$empresa_id] = [
        'id' => $empresa_id,
        'active' => (int) $empresa->isActive(),
        'nombre' => $empresa->getNombreFormato(),
        'selected' => $empresa_id == $empresa_id_selected,
      ];

      if ($dataSunat) {
        $empresas_format[$empresa_id]['ruc'] = $empresa->EmpLin1;
        $empresas_format[$empresa_id]['c_sol'] = $empresa->FE_UCLAVE;
        $empresas_format[$empresa_id]['u_sol'] = $empresa->FE_USUNAT;
      }
    }

    return $empresas_format;
  }

  public static function formatListPendientes($empresas_pendientes = [])
  {
    $empresas_pendientes = (array) $empresas_pendientes;
    $empresas_pendientes['empresas'] = (array) $empresas_pendientes['empresas'];
    $empresas_format = [];
    $empresas =  Empresa::whereIn('empcodi', array_keys((array) $empresas_pendientes['empresas']))->get();
    $empresa_id_selected = session()->get('empresa_id');

    foreach ($empresas as $empresa) {
      // ------------------------------------------------------------ \\
      $empresa_id = $empresa->id();
      $ambiente_str = $empresa->isProduction() ? 'PROD' : 'DESR';
      $pendientes_str = sprintf('PEND (%s) ', $empresas_pendientes['empresas'][$empresa_id]->cant);

      $nombre = sprintf(
        "%s | %s | %s | %s %s ",
        $empresa_id,
        $ambiente_str,
        $empresa->ruc(),
        $empresa->nombre(),
        $pendientes_str
      );

      $empresas_format[$empresa->empcodi] = [
        'id' => $empresa_id,
        'nombre' => $nombre,
        'active' => $empresa->active,
        'selected' => $empresa_id == $empresa_id_selected,
      ];
    }
    return $empresas_format;
  }

  public function sendPendientes()
  {
    (new sendPendientes($this))->handle();
  }

  public function toggleEstado()
  {
    $this->update(['active' => $this->active ? 0 : 1]);
  }

  public function sendGuiasPendientes()
  {
    (new sendGuiasPendientes($this))->handle();
  }

  public function createSuscripcionDefault()
  {
  }

  public function planesDuraciones()
  {
    return $this->hasMany(PlanDuracion::class, 'empresa_id', 'empcodi');
  }

  public function createPlanes($is_plan_demo = null)
  {
    (new CreatePlanes($this, $is_plan_demo))->handle();
  }

  public function serieExists($serie = null)
  {
    return $this->locales->where('SerLetra', $serie)->count();
  }

  public function nuevoNumeroSerie()
  {
    $local = $this->locales->sortByDesc('LocCodi')->first();
    $serLetra = $local->SerLetra;
    $nueva_serie = "";

    if (is_numeric($serLetra)) {
      $nuevoNumero = (int) $serLetra + 1;
      $nueva_serie = math()->addCero($nuevoNumero, 3);
    } else {
      $serLetraTwoDigits = substr($serLetra, -2);


      if (is_numeric($serLetraTwoDigits)) {
        $nuevoNumero = (int) $serLetraTwoDigits + 1;
        $nuevaTwoDigits = math()->addCero($nuevoNumero, 2);
        $nueva_serie = $serLetra[0] . $nuevaTwoDigits;
      } else {
        $nueva_serie = "000";
      }
    }

    return $nueva_serie;
  }


  public function getNuevaSeriesInfo($serie = null, $add_plantillas = false)
  {
    $serie = $serie ?? $this->nuevoNumeroSerie();

    $series = [
      ['first_letter' => 'F', 'tidcodi' => '01', 'codigo' => 'tidcodi_01',  'nombre'   => 'Factura', 'serie' =>  'F' . $serie, 'defecto' => 1, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'B', 'tidcodi' => '03', 'codigo' => 'tidcodi_03',  'nombre'   => 'Boleta', 'serie' =>  'B' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '07', 'codigo' => 'tidcodi_07-01', 'nombre' => 'N.Credito Fact.', 'serie' =>  'F' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '07', 'codigo' => 'tidcodi_07-03', 'nombre' => 'N.Credito Bole.', 'serie' =>  'B' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '08', 'codigo' => 'tidcodi_08-01', 'nombre' => 'N.Debito Fact.', 'serie' =>  'F' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '08', 'codigo' => 'tidcodi_08-03', 'nombre' => 'N.Debito Bole.', 'serie' =>  'B' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'T', 'tidcodi' => '09', 'codigo' => 'tidcodi_09', 'nombre' => 'Guia Remisión', 'serie' =>  'T' . $serie, 'defecto' => 0, 'tipo' => 'guias', 'correlativo' => '0'],

      ['first_letter' => 'P', 'tidcodi' => '50', 'codigo' => 'tidcodi_50', 'nombre' => 'Proforma', 'serie' => 'P' . $serie, 'defecto' => 0, 'tipo' => 'cotizaciones', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '52', 'codigo' => 'tidcodi_52', 'nombre' => 'Nota De Venta', 'serie' =>  'N' . $serie, 'defecto' => 0, 'tipo' => 'ventas', 'correlativo' => '0'],

      ['first_letter' => 'N', 'tidcodi' => '53', 'codigo' => 'tidcodi_53', 'nombre' => 'Pre Venta', 'serie' => 'N' . $serie, 'defecto' => 0, 'tipo' => 'cotizaciones', 'correlativo' => '0'],

      ['first_letter' => 'O', 'tidcodi' => '98', 'codigo' => 'tidcodi_98', 'nombre' => 'Ord. Pago', 'serie' => 'O' . $serie, 'defecto' => 0, 'tipo' => 'cotizaciones', 'correlativo' => '0'],
    ];

    if ($add_plantillas) {
      $series = (new GetSeriesDefecto($series))->handle();
    }

    return (object) [
      'series' => $series,
      'serie' => $serie,
    ];
  }

  public function tomaInventarios()
  {
    return $this->hasMany(TomaInventario::class, 'empcodi', 'empcodi');
  }

  public function hasTomaInventarioPendiente($loccodi)
  {
    return (bool) $this->tomaInventarios
      ->where('LocCodi', $loccodi)
      ->where('InvEsta', TomaInventario::ESTADO_PENDIENTE)
      ->count();
  }

  public function resetData()
  {
    return (new resetData($this))->handle();
  }

  public function deleteAllFiles()
  {
    $fh = fileHelper($this->ruc());
    $fh->deleteAllInfo();
  }

  public function deleteData($items = null)
  {
    return (new deleteData($this, $items))->handle();
  }

  public function updateDataAdicional(array $data_adicional_new, $sobreEscribir = true)
  {
    $data_adicional_current  = (array) ($this->getDataAditional() ?? []);
    $data_aditional = $sobreEscribir ?
      array_merge($data_adicional_current, $data_adicional_new) :
      array_merge($data_adicional_new, $data_adicional_current);
    $data_json = json_encode($data_aditional);
    $this->opcion->update(['LogEmpr' => $data_json]);
    $this->cleanCache();
  }

  public function deleteLogo($logo_id)
  {
    if ($logo_id == "1") {
      $this->EmpLogo = null;
    } elseif ($logo_id == "2") {
      $this->EmpLogo1 = null;
    } elseif ($logo_id == "3") {
      $this->EmpDWeb = null;
    } elseif ($logo_id == "4") {
      $this->FE_RESO = null;
    } else if ($logo_id == "5") {
      $img_link = $this->img_footer;


      if ($img_link) {
        if (strpos($img_link, config('app.images.footer_banner_name')) === false) {
          $nameImg = last(explode("\\", $img_link));
          $fp = FileHelper();
          $fp->only_nube = true;
          $fp->delete_img($nameImg);
        }
      }

      $this->updateDataAdicional(['footer_img' => '']);
    }
    $this->save();
    $this->cleanCache();
  }


  public function setLogoFooterSainfo()
  {
    $this->deleteLogo("5");
    $this->updateDataAdicional(['footer_img' => getLogoFooterSainfo()]);
    $this->cleanCache();
  }

  public function ubigeo()
  {
    return $this->belongsTo(Ubigeo::class, 'FE_UBIGEO', 'ubicodi');
  }

  public function getLocalDireccion($id)
  {
    $local = Local::find($id);

    if( $local->isDireccionInd() ){
      return $local->LocDire;
    }

    return $this->getDirecciones();
  }

  public function getDirecciones()
  {

    $direcciones = $this->locales->pluck('LocDire', 'LocNomb');
    $direcciones_html = "";
    $first = true;
    $dir_count = $direcciones->count();

    foreach ($direcciones as $localNombre => $direccion) {
      // 
      if ($first) {
        $direcciones_html = $dir_count == 1 ?
          sprintf('%s', $direccion) :
          sprintf('<span class="bold">%s</span>: %s', $localNombre, $direccion);
        $first = false;
      } else {
        $direcciones_html .= sprintf('<br><span class="bold">%s</span>: %s', $localNombre, $direccion);
      }
    }

    return $direcciones_html;
  }

  public function syncMedioPagos($tipos_pagos = null)
  {
    $tipos_pagos = $tipos_pagos ?? (new TipoPagoRepository(new TipoPago()))->all();

    (new SyncWithMainTable($this, $tipos_pagos))->handle();
  }

  public function medios_pagos()
  {
    return $this->hasMany(MedioPago::class, 'empcodi', 'empcodi');
  }

  public function getPlanRegistroNombre()
  {
    if ($plan_id = $this->{self::CAMPO_PLAN_REGISTRO}) {
      $plan = Plan::find($plan_id);
      return $plan ? $plan->codigo : 'NINGUNO';
    }
    return "NINGUNO";
  }

  public function  updateModulos($data)
  {
    (new UpdateModulos($this, $data))->handle();
  }
}