<?php

namespace App;

use App\Jobs\LocalAsociateInfo;
use App\Jobs\LocalUpdateAsociateInfo;
use App\Models\UserLocal\UserLocal;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Util\ModelUtil\ModelUtil;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
  use 
  UsesTenantConnection,
  ModelUtil,
  ModelEmpresaScope;

  protected $table = "local"; 
  protected $primaryKey = "LocCodi";
  protected $keyType = "string";
  public $timestamps = false;
  public $incrementing = false;
  const INIT = "001";
  const LOCAL_INAVAILABLE = "000";
  const DEFAULT_LOCAL = "001";
  const EMPRESA_CAMPO = "EmpCodi";
  public $fillable = [
    'LocCodi',
    'LocNomb',
    'LocNombre',
    'LocDire',
    'LocDist',
    'SerGuiaSal',
    'LocTele',
    'NumGuiaSal',
    'Numlibre',
    'SerLetra',
    'Numletra',
    'Fecha',
    'PDFLocalNombreInd',
    'EmpCodi'
  ];
  // ----------------------------------------------------------------------------------------------------------------------------------

  public static function lasId()
  {
    return agregar_ceros(Local::max('LocCodi'),3);
  }

  public static function createLocalPrincipal( $empresa )
  {
  } 

  public function isDireccionInd()
  {
    return $this->PDFLocalNombreInd == "0";
  }


  public static function createLocalDefault( $empcodi, $direccion = "", $tlf = null, $ubigeo = null, $nombre = null  )
  {
    $nombre = $nombre ?? 'PRINCIPAL'; 
    $tlf = $tlf ?? '';
    $direccion = $direccion ?? '';
    $ubigeo = $ubigeo ?? '';

    $data = [];
    $data['LocCodi'] = self::INIT;      
    $data['LocNomb'] = $nombre;
    $data['LocDire'] = $direccion;
    $data['LocDist'] = $ubigeo;
    $data['LocTele'] = $tlf;
    $data['SerGuiaSal'] = "000";
    $data['Numlibre'] = "10";
    $data['Numletra'] = "000000";
    $data['SerLetra'] = "000";
    $data['Fecha'] = date('Y-m-d');
    self::create($data);
  }

  public function elegible()
  {
    return $this->LocCodi !== self::LOCAL_INAVAILABLE;
  }

  public function default()
  {
    return $this->LocCodi === self::DEFAULT_LOCAL;
  }

  public function empresa()
  {
    return $this->belongsTo( Empresa::class , 'EmpCodi' , 'empcodi' );
  }

  public static function getNextID()
  {
    return agregar_ceros(self::max('LocCodi'),3,1);
  }

  public static function getNumlibre()
  {
    $count = Local::count();
    return $count + 10;
  }

  public function series()
  {
    return $this->hasMany( SerieDocumento::class, 'loccodi' , 'LocCodi' );
  }
  
  public function seriesGuiaRemision(){
    
    return $this->series
    ->where( 'tidcodi' ,  TipoDocumentoPago::guiaremisionId() )
    ->where( 'usucodi' ,   auth()->user()->usucodi )
    ->all();
  }

  public function listas()
  {
    return $this->hasMany( ListaPrecio::class, 'LocCodi' , 'LocCodi' );
  }

  public function usuarios_documentos()
  {
    return $this->hasMany( SerieDocumento::class, 'loccodi', 'LocCodi' );
  }

  public function concatTlf()
  {
    // return true;
    return $this->Empcodi == "118";
  }


  public function getResumenCode()
  {
    return $this->Numlibre;
  }

  public function ubigeo()
  {
    return $this->belongsTo(Ubigeo::class, 'LocDist', 'ubicodi');
  }

  public function ventas()
  {
    return $this->hasMany( Venta::class, 'LocCodi', 'LocCodi' );
  }

  public function resumenes()
  {
    return $this->hasMany(Resumen::class, 'LocCodi', 'LocCodi');
  }

  public function cajas()
  {
    return $this->hasMany(Caja::class, 'LocCodi', 'LocCodi');
  }

  public function compras()
  {
    return $this->hasMany(Compra::class, 'LocCodi', 'LocCodi');
  }

  public function cotizaciones()
  {
    return $this->hasMany(Cotizacion::class, 'LocCodi', 'LocCodi');
  }

  public function usuarios_locales()
  {
    return $this->hasMany(UserLocal::class, 'loccodi', 'LocCodi');
  }

  public function asociateInfo( $request )
  {
    (new LocalAsociateInfo($this, $request))->handle();
  }

  public function updateAsociateInfo( $request )
  {
    (new LocalUpdateAsociateInfo($this, $request))->handle();
  }

  public function codLocal()
  {
    return substr($this->LocCodi,-1);
  }

  public function getSeries()
  {
    $user = $this->empresa->userOwner();

    $series = $user->documentos
    ->where('loccodi' , $this->LocCodi )
    ->where('empcodi', $this->EmpCodi );

    $data = [];
    
    foreach( $series as $serie ){
      $data[] = [
        'serie' => $serie->sercodi,
        'correlativo' => $serie->numcodi,
        'nombre' => TipoDocumentoPago::getNombreDocumento($serie->tidcodi),
      ];
    }

    return $data;
  }

  public function deleteAll()
  {
    //Eliminar series
    $series = SerieDocumento::where('empcodi', $this->EmpCodi )
    ->where('loccodi', $this->LocCodi);
    foreach ($series as $serie ) {
      $serie->delete();
    }

    //Eliminar usuario_locales
    $u_locales = UserLocal::where('empcodi', $this->EmpCodi )
      ->where( 'loccodi', $this->LocCodi );
    foreach ($u_locales as $u_local ) {
      $u_local->deleteShort();
    }
  }

  public function getLocalAttribute()
  {
   return $this; 
  }
}