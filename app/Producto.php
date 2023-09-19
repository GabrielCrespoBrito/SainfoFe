<?php

namespace App;

use App\Models\Producto\Attribute\ProductoAttribute;
use App\Models\Producto\Method\ProductoMethod;
use App\Models\Producto\Relationship\ProductoRelationship;
use App\Models\Sunat\SunatProducto;
use App\Models\Traits\InteractWithStock;
use App\Presenter\ProductoPresenter;
use App\Producto\InventaryTrait;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
  use
    UsesTenantConnection,
    InventaryTrait,
    ProductoMethod,
    InteractWithStock,
    ProductoRelationship,
    ProductoAttribute;

  protected $primaryKey = 'ID';
  protected $keyType = "string";
  public $incrementing = false;
  const EMPRESA_CAMPO = 'empcodi';
  const CREATED_AT = 'User_FCrea';
  const UPDATED_AT = 'User_FModi';
  const ID_INIT = 100001;
  public $fillable = [
    'ID',
    "profoto2",
    "proesta",
    'ProCodi',
    'famcodi',
    'grucodi',
    'marcodi',
    'ProNomb',
    'unpcodi',
    'moncodi',
    'ProPUCD',
    'ProPUCS',
    'ProMarg',
    'ProPUVD',
    'ProPUVS',
    'ProPMVS',
    'ProPMVD',
    'ProPeso',
    'ProUltC',    
    'BaseIGV',
    'tiecodi',
    'ProCodi1',
    'Promini',
    'ProPerc',
    'prosto1',
    'prosto2',
    'prosto3',
    'prosto4',
    'prosto5',
    'prosto6',
    'prosto7',
    'prosto8',
    'prosto9',
    'prosto10',
    'icbper',
    'ISC',
    'incluye_igv',
    'empcodi'
  ];

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('empresa', function ($query) {
      $empcodiField = self::EMPRESA_CAMPO ?? 'empcodi';
      return $query->where($empcodiField, empcodi());
    });

    static::addGlobalScope('noEliminados', function ($query) {
      return $query->where('UDelete', '=', '0');
    });
  }

  public function getProPUVDAttribute($value)
  {
    return is_null($value) ? "0.00" : $value;
  }

  public function unis()
  {
    return $this->hasMany(Unidad::class, 'Id', 'ID')
      ->where('empcodi', $this->empcodi);
  }

  public function getProPUVSAttribute($value)
  {
    return is_null($value) ? "0.00" : $value;
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'empcodi', 'EmpCodi');
  }

  public function moneda()
  {
    return $this->belongsTo(Moneda::class, 'moncodi', 'moncodi');
  }

  public static function findByProCodi($procodi)
  {
    return self::where('ProCodi', $procodi)->first();
  }

  public function isEliminado()
  {
    return $this->UDelete == "*";
  }


  public static function UltimoID($empcodi = null)
  {
    return agregar_ceros(Producto::withoutGlobalScope('noEliminados')->max('id'), 6, 1);
  }

  public function setIDAttribute($value)
  {
    $this->attributes['id'] = $value ?? self::UltimoID();
  }

  public function lastID()
  {
    return Producto::where('empcodi', empcodi())->max('ID');
  }

  public function unidades()
  {
    return $this->hasMany(Unidad::class, 'Id', 'ID');
  }

  public function unidades_()
  {
    return $this->hasMany(UnidadProductoSelect::class, 'Id');
  }


  public function marca()
  {
    return $this->belongsTo(Marca::class, 'marcodi', 'MarCodi');
  }

  public function marca_()
  {
    return $this->belongsTo(Marca::class, 'marcodi', 'MarCodi');
  }

  public function setProNombAttribute($value)
  {
    $this->attributes['ProNomb'] = strtoupper($value);
  }

  public static function consultar_noperacion($grupo, $familia, $marca)
  {
    $agrupacion_codigo = $grupo . $familia . $marca;
    $ultimo_parte = "00";

    $same_tipo =
      Producto::where('grucodi', $grupo)
      ->where('famcodi', $familia)
      ->where('marcodi', $marca)
      ->where('empcodi', empcodi());

    if ($same_tipo->count()) {
      $ultimo_digitos = (int) substr($same_tipo->max('ProCodi'), -2);
      $ultimo_parte = $ultimo_digitos <= 90 ? $ultimo_digitos + 5 : $ultimo_digitos + 1;
    }

    $ultimo_parte = agregar_ceros($ultimo_parte, 2, 0);
    $value = $agrupacion_codigo . $ultimo_parte;

    return $value;
  }

  public function calcularPrecioVenta($precio_venta)
  {
    return self::calcularPrevioVentaStatic($precio_venta, $this->moncodi);
  }

  public static function calcularPrevioVentaStatic($precio_venta, $moneda)
  {
    $tc = get_empresa()->opcion->tipo_cambio_publico;

    if ($moneda == Moneda::SOL_ID) {
      $precio_venta_sol = $precio_venta;
      $precio_venta_dolar = $precio_venta / $tc;
    } else {
      $precio_venta_sol = $precio_venta * $tc;
      $precio_venta_dolar = $precio_venta;
    }

    return [
      'sol' => $precio_venta_sol,
      'dolar' => $precio_venta_dolar
    ];
  }

  public static function calcularPrecioMin($precio_min, $moneda)
  {
    $precio_sol = 0;
    $precio_dolar = 0;

    if( $precio_min != 0 ){
      $tc = get_empresa()->opcion->tipo_cambio_publico;
      if ($moneda == Moneda::SOL_ID) {
        $precio_sol = $precio_min;
        $precio_dolar = $precio_min / $tc;
      } else {
        $precio_sol = $precio_min * $tc;
        $precio_dolar = $precio_min;
      }
    }

    return [
      'sol' => $precio_sol,
      'dolar' => $precio_dolar
    ];
  }
  

  public function presenter()
  {
    return new ProductoPresenter($this, func_get_args());
  }

  public function descontarInventario($cantidad_descontar = null)
  {
    // $this->
  }

  public static function createDefault($empcodi, $lisCodi, $producto_nombre = null, $codigo = null)
  {
    $data["ProNomb"] = $producto_nombre ?? "PRODUCTO POR DEFECTO";
    $data["BaseIGV"] = "GRAVADA";
    $data["ProCodi"] =  $codigo ?? "00000001";
    $data["marcodi"] = '00';
    $data["grucodi"] = '00';
    $data["famcodi"] = '00';
    $data["proesta"] = '1';
    $data["prosto1"] = '0';
    $data["prosto2"] = '0';
    $data["prosto3"] = '0';
    $data["prosto4"] = '0';
    $data["prosto5"] = '0';
    $data["prosto6"] = '0';
    $data["prosto7"] = '0';
    $data["prosto8"] = '0';
    $data["prosto9"] = '0';
    $data["ISC"] = '0';
    $data["prosto10"] = '0';
    $data["ProPUCD"] = 0;
    $data["ProPUCS"] = 0;
    $data["ProMarg"] = 0;
    $data["ProPUVD"] = 0;
    $data["ProPUVS"] = 0;
    $data["ProPeso"] = 1;
    $data["unpcodi"] = 'NIU';
    $data["tiecodi"] = '01';
    $data["moncodi"] = '01';
    $data["empcodi"] = $empcodi;

    $productoId = Producto::insertGetId($data);

    Producto::createDefaultUnit($lisCodi, $productoId, $data);

    return true;
  }


  public static function createDefaultUnit($lisCodi, $id, $data)
  {
    $unidad = new Unidad;
    $unidad->Id = $id;
    $unidad->UniEnte = 1;
    $unidad->UniMedi = 1;
    $unidad->UniAbre = $data['unpcodi'];
    $unidad->UniPUCD = $data['ProPUCD'];
    $unidad->UniPUCS = $data['ProPUCS'];
    $unidad->UniMarg = $data['ProMarg'];
    $unidad->UNIPUVD = $data['ProPUVD'];
    $unidad->UNIPUVS = $data['ProPUVS'];
    $unidad->UniPeso = $data['ProPeso'];
    $unidad->UniPAdi  = 0;
    $unidad->UniMarg1 = 0;
    $unidad->UniPUVS1 = 0;
    $unidad->UniPUVD1 = 0;
    $unidad->LisCodi = $lisCodi;
    $unidad->empcodi = $data['empcodi'];
    $unidad->save();
  }

  public function codSunat()
  {
    return $this->belongsTo(SunatProducto::class, 'profoto2', 'id');
  }


  public static function getLastCompra($procodi, $fecha, $local = null, $withProveedor = false)
  {
    $query = DB::connection('tenant')->table('compras_detalle')
      ->join('compras_cab', function ($join) use ($fecha, $local) {
        $join->on('compras_cab.CpaOper', '=', 'compras_detalle.CpaOper');

        if ($fecha) {
          $join->where('compras_cab.CpaFCon', '<=',  $fecha);
        }

        if ($local) {
          $join->where('compras_cab.LocCodi', '=',  $local);
        }
      });

    if ($withProveedor) {
      $query->join('prov_clientes', function ($join) {
        $join->on('prov_clientes.PCCodi', '=', 'compras_cab.PCcodi')
          ->where('prov_clientes.TipCodi', '=', 'P');
      });
    }

    $columns = [
      'compras_cab.CpaFCpa',
      'compras_cab.CpaOper',
      'compras_cab.moncodi',
      'compras_cab.CpaTCam',
      'compras_detalle.Linea',
      'compras_detalle.DetCant',
      'compras_detalle.DetCSol',
      'compras_detalle.DetCDol',
      'compras_detalle.DetPrec',
      'compras_detalle.Detfact',
    ];

    if ($withProveedor) {
      $columns[] = 'prov_clientes.PCCodi';
      $columns[] = 'prov_clientes.PCNomb';
    }

    return $query
      ->where('compras_detalle.Detcodi', '=', $procodi)
      ->select($columns)
      ->orderByDesc('CpaFCPa')
      ->orderByDesc('Linea')
      ->first();
  }


  public static function getProductCostos(
    $procodi,
    $fecha,
    $local,
    $cantidad,
    $costo_producto_dolar,
    $costo_producto_soles,
    $withProveedor = false,
    $factor_venta = 1
  ) {
    $ultima_compra = self::getLastCompra($procodi, $fecha, $local, $withProveedor);

    if ($ultima_compra) {
      $costo_sol_unitario = ($ultima_compra->DetCSol / $ultima_compra->DetCant);
      $costo_dolar_unitario = ($ultima_compra->DetCDol / $ultima_compra->DetCant);
      $factor_multiplicador = $factor_venta / $ultima_compra->Detfact;
      $costo_sol =  $costo_sol_unitario * ($cantidad * $factor_multiplicador);
      $costo_dolar = $costo_dolar_unitario * ($cantidad * $factor_multiplicador);
    } else {
      $costo_sol = $costo_producto_soles * $cantidad;
      $costo_dolar = $costo_producto_dolar * $cantidad;
    }

    $data = [
      '01' => $costo_sol,
      '02' => $costo_dolar,
      'ultima_compra' => $ultima_compra
    ];

    return $data;
  }

  /**
   * Obtener el costo del producto en un item
   * 
   * @return float
   */
  public function getCostos($fecha = null, $local = null, $cantidad, $factor_venta = 1)
  {
    return self::getProductCostos($this->ProCodi, $fecha, $local, $cantidad, $this->ProPUCD, $this->ProPUCS, true, $factor_venta);
  }

  /**
   * Obtener los stock 
   *
   * @return array
   */
  public function getStocks()
  {
    $total = $this->prosto1 + $this->prosto2 + $this->prosto3 + $this->prosto4 + $this->prosto5 + $this->prosto6 + $this->prosto7 + $this->prosto8 + $this->prosto9 + $this->prosto10;

    return [
      'prosto1' =>  $this->prosto1,
      'prosto2' =>  $this->prosto2,
      'prosto3' =>  $this->prosto3,
      'prosto4' =>  $this->prosto4,
      'prosto5' =>  $this->prosto5,
      'prosto6' =>  $this->prosto6,
      'prosto7' =>  $this->prosto7,
      'prosto8' =>  $this->prosto8,
      'prosto9' =>  $this->prosto9,
      'prosto10' =>  $this->prosto10,
      'total' =>  $total,
    ];
  }

  public function getStock($loccodi_codi)
  {
    $stock_name = "prosto" . $loccodi_codi;
    return $this->{$stock_name};
  }

  public function getStockFromLocCodi($loccodi)
  {
    return $this->getStock(substr($loccodi, -1));
  }

  /**
   * Buscar producto por codigo de barra
   * 
   * @return mixed
   */
  public static function findByCodigoBarra($codigo_barra)
  {
    return self::where('ProCodi1', $codigo_barra)->first();
  }

  public static function getProductoByNombre($nombre, $empcodi, $lista)
  {
    $producto = self::where('ProNomb', $nombre)->first();

    if ($producto) {
      return $producto;
    }

    $producto = Producto::where('ProCodi', 'P')->first();

    if ($producto) {
      return $producto;
    }
    return self::createDefault($empcodi, $lista, 'PRODUCTO ESCRIBIR', 'P');
  }

  public function isSol()
  {
    return $this->moncodi === Moneda::SOL_ID;
  }

  public function updatePrecioByUnidadPrincipal()
  {
    $unidad = $this->unidadPrincipal();

    $this->update([
      'ProPUCD' => $unidad->UniPUCD,
      'ProPUCS' => $unidad->UniPUCS,
      'ProMarg' => $unidad->UniMarg,
      'ProPUVD' => $unidad->UNIPUVD,
      'ProPUVS' => $unidad->UNIPUVS,
      'ProPeso' => $unidad->UniPeso,
    ]);
  }


  public function unidadPrincipal()
  {
    return $this->unidades->first();
  }

  public function updatePrecioUnidadPrincipal()
  {
    $unidad = $this->unidadPrincipal();

    $unidad->update([
      'UniPUCD' => $this->ProPUCD,
      'UniPUCS' => $this->ProPUCS,
      'UniMarg' => $this->ProMarg,
      'UNIPUVD' => $this->ProPUVD,
      'UNIPUVS' => $this->ProPUVS,
      'UniPeso' => $this->UNIPUVS
    ]);
  }


  public function incluyeIgv()
  {
    return (bool) $this->incluye_igv;
  }
}
