<?php

namespace App\Helpers;

use App\Grupo;
use App\Moneda;
use App\TipoPago;
use App\Vehiculo;
use App\Detraccion;
use App\ListaPrecio;
use App\Procedencia;
use App\TipoCliente;
use App\Departamento;
use App\PDFPlantilla;
use App\TipoDocumento;
use App\Transportista;
use App\MotivoTraslado;
use App\TipoExistancia;
use App\UnidadProducto;
use App\TipoNotaCredito;
use App\EmpresaTransporte;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheHelper\ModelName;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class CacheHelper
{
  use ModelName;

  # Codigo de la empresa para almacenar cache por empresa
  protected $empcodi;

  # Listado de todos los nombre de los recursos cacheados
  public $namesCache = [];

  public function __construct()
  {
    $this->empcodi = empcodi();
    $this->generateNames();
  }

  /**
   * Generar nombre de cache dependiendo los nombres de los metodos
   * 
   * @example transportista_allCacheable = transportista.all 
   * @return void
   */
  public function generateNames()
  {
    foreach (get_class_methods($this) as $nameMethod) {
      $pos =  strpos($nameMethod, "Cacheable");
      if ($pos !== false) {
        $nameCache = str_replace("_", ".", strtolower(substr($nameMethod, 0, $pos)));
        $this->namesCache[$nameCache] = ['nameCache' => $nameCache, 'nameMethod' => $nameMethod];
      }
    }
  }

  public function getNameCache($nameCache)
  {
    if (array_key_exists($nameCache, $this->namesCache)) {
      return $this->namesCache[$nameCache];
    } else {
      throw new \Exception("No hay nada guardado en cache, con el nombre {$nameCache}", 1);
    }
  }


  /**  
   * Obtener una información de cache
   * @param String $nameCache 
   * @param Array $args 
   * @return Exception|Collection
   **/
  public function get($nameCache, ...$args)
  {
    $cacheElement = $this->getNameCache($nameCache);
    $nameCacheByEmpresa = $this->getRealNameCache($cacheElement['nameCache']);
    return $this->{$cacheElement['nameMethod']}($nameCacheByEmpresa, $args);
  }

  /**
   * Obtener el nombre de cache real dependiendo de la empresa
   *
   * @param $name String
   * @return String
   */
  public function getRealNameCache($name)
  {
    return $name . $this->empcodi;
  }


  /**
   * Borrar data del cache
   * @param String $nameCache
   * 
   * @return bool
   */

  public function deleteCache($name)
  {
    Cache::forget($this->getRealNameCache($this->getNameCache($name)['nameCache']));
  }

  public function forget($name)
  {
    $this->deleteCache($name);
  }

  public function flush()
  {
    Cache::flush();
  }

  ############# Lista de elementos cacheados #############

  public function tipocliente_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return TipoCliente::all();
    });
  }

  public function pdfPlantillas_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return PDFPlantilla::all();
    });
  }

  public function pdfPlantillas_defectoCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return PDFPlantilla::where('default', 1)->get();
    });
  }

  public function grupo_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Grupo::noDeleted()->get();
    });
  }

  public function motivotraslado_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return MotivoTraslado::all();
    });
  }

  public function tipodocumento_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return TipoDocumento::all();
    });
  }

  public function departamento_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Departamento::all();
    });
  }

  public function listaprecio_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return ListaPrecio::all();
    });
  }

  public function transportista_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Transportista::all();
    });
  }

  public function empresatransporte_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return EmpresaTransporte::all();
    });
  }

  public function vehiculo_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Vehiculo::all();
    });
  }


  public function moneda_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Moneda::all();
    });
  }

  public function tiponotacredito_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return TipoNotaCredito::all();
    });
  }

  public function detraccion_activeCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Detraccion::where('active', 1)->orderBy('cod_sunat')->get();
    });
  }

  public function detraccion_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Detraccion::all();
    });
  }


  public function tipopago_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return TipoPago::all();
    });
  }
  
  public function docstatus_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return StatusCode::all();
    });
  }

  public function unidadproducto_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return UnidadProducto::all();
    });
  }

  public function tipoexistencia_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return TipoExistancia::all();
    });
  }

  public function procendencia_allCacheable($nameCache)
  {
    return Cache::rememberForever($nameCache, function () {
      return Procedencia::all();
    });
  }

  

}
