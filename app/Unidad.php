<?php

namespace App;

use App\Helpers\HasCompositePrimaryKey;
use App\Models\Unidad\Attribute\UnidadAttribute;
use App\Models\Unidad\Method\UnidadMethod;
use App\Models\Unidad\Relationship\UnidadRelationship;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
  use
    UnidadAttribute,
    UsesTenantConnection,
    UnidadRelationship,
    ModelEmpresaScope,
    UnidadMethod;

    const EMPRESA_CAMPO = "empcodi";
    protected $table = 'unidad';
    protected $primaryKey = "Unicodi";
    protected $keyType = "string";
    public $timestamps = false;
    public $fillable = [
    "Id",
    "UniEnte",
    "UniMedi",
    "UniAbre",
    "UniPeso",
    "UniPUCD",
    "UniPUCS",
    "UniMarg",
    "UNIPUVD",
    "UNIPUVS",
    "UNIPMVS",
    "UNIPMVD",    
    "UniPAdi",
    "Unicodi",
    "LisCodi",
    "empcodi"
  ];

  public function lista()
  {
    return $this->belongsTo(ListaPrecio::class, 'LisCodi', 'LisCodi');
  }

  public function withListaName()
  {
    return $this->UniAbre . ' - ' . $this->lista->LisNomb;
  }

  public function producto_filter()
  {
    return $this->hasOne(Producto::class, 'ID', 'Id');
  }

  public function prod()
  {
    return $this->belongsTo(Producto::class, 'ID', 'Id');
  }

  public function producto()
  {
    return Producto::find($this->Id);
  }

  public static function getUniCodi($id_producto)
  {
    $cantUnidades = ((int) substr(self::where('Id', $id_producto)->max('Unicodi'), -2)) + 1;
    $codeUni = $cantUnidades < 10 ? "0" . (string) $cantUnidades : $cantUnidades;
    return $id_producto . $codeUni;
  }

  public function setUniPUCDAttribute($value)
  {
    $this->attributes["UniPUCD"] = $value;
  }

  public function getUniPUCDAttribute($value)
  {
    return $value;
  }
  public function getUniPUVDAttribute($value)
  {
    return $value;
  }


  public static function createLista($unidad, $save, $producto, $lista)
  {
    $u = $save ? new self : $unidad;

    if ($save) {
      // $u->UniCodi = self::getUniCodi($producto->ID);
      $u->Id = $producto->ID;
    }

    $u->UniEnte = 1;
    $u->UniMedi = 1;
    $u->UniAbre = $producto->unpcodi;
    $u->UniPUCD = $producto->ProPUCD;
    $u->UniPUCS = $producto->ProPUCS;
    $u->UniMarg = $producto->ProMarg;

    // ---------------------------------------
    $u->UNIPUVD = $producto->ProPUVD;
    $u->UNIPUVS = $producto->ProPUVS;
    // ---------------------------------------
    $u->UniPeso = $producto->ProPeso;
    $u->UniPAdi  = 0;
    $u->UniMarg1 = 0;
    $u->UniPUVS1 = 0;
    $u->UniPUVD1 = 0;
    $u->LisCodi = $lista->LisCodi;
    $u->empcodi = $producto->empcodi;
    $u->save();

    if (!$save) {
      return;
    }
  }

  public static function createFromProducto($productId, $productData, $listas)
  {
    $listas = $listas ?? get_empresa()->listas;

    foreach ($listas as $lista) {
      $data = [];
      $data['Id'] = $productId;
      $data['UniEnte'] = 1;
      $data['UniMedi'] = 1;
      $data['UniAbre'] = $productData['unpcodi'];
      $data['UniPUCD'] = $productData['ProPUCD'];
      $data['UniPUCS'] = $productData['ProPUCS'];
      $data['UniMarg'] = $productData['ProMarg'];
      $data['UNIPUVD'] = $productData['ProPUVD'];
      $data['UNIPUVS'] = $productData['ProPUVS'];
      $data['UNIPMVD'] = $productData['ProPMVD'] ?? 0;
      $data['UNIPMVS'] = $productData['ProPMVS'] ?? 0;
      $data['UniPeso'] = $productData['ProPeso'];
      $data['UniPAdi']  = 0;
      $data['UniMarg1'] = 0;
      $data['UniPUVS1'] = 0;
      $data['UniPUVD1'] = 0;
      $data['LisCodi'] = $lista->LisCodi;
      $data['empcodi'] = $productData['empcodi'];
      if(Unidad::insert($data) == false){
        return false;
      }
    }

    return true;
  }



  public static function save_unidad($producto, $save = true, $unidad = false, $listas = null)
  {
    $listas = $listas ?? $producto->empresa->listas;

    foreach ($listas as $lista) {

      $u = $save ? new self : $unidad;

      if ($save) {
        // $u->UniCodi = self::getUniCodi($producto->ID);
        $u->Id = $producto->ID;
      }

      $u->UniEnte = 1;
      $u->UniMedi = 1;
      $u->UniAbre = $producto->unpcodi;
      $u->UniPUCD = $producto->ProPUCD;
      $u->UniPUCS = $producto->ProPUCS;
      $u->UniMarg = $producto->ProMarg;
      $u->UNIPUVD = $producto->ProPUVD;
      $u->UNIPUVS = $producto->ProPUVS;
      $u->UniPeso = $producto->ProPeso;
      $u->UniPAdi  = 0;
      $u->UniMarg1 = 0;
      $u->UniPUVS1 = 0;
      $u->UniPUVD1 = 0;
      $u->LisCodi = $lista->LisCodi;
      $u->empcodi = $producto->empcodi;
      $u->save();

      if (!$save) {
        return;
      }
    }
  }

  public static function save_adicional($producto, $data, $index)
  {
    $u = new self;
    // UniCodi
    // $u->UniCodi = self::getUniCodi($producto->ID);
    // Id del producto
    $u->Id = $producto->ID;
    // Cantidad de la medida inicial
    $u->UniEnte = returnValueUnidad('uniente', $data, $index, 1);
    // Cantidad de la nueva unidad
    $u->UniMedi = returnValueUnidad('unimedi', $data, $index, 1);
    // Codigo de la unidad
    $u->UniAbre = returnValueUnidad('uniabre', $data, $index, 'UNI');
    // Costo en dolares
    $u->UniPUCD = returnValueUnidad('unipucd', $data, $index, 0);
    // Costoen soles
    $u->UniPUCS = returnValueUnidad('unipucs', $data, $index, 0);
    // Margen
    $u->UniMarg = returnValueUnidad('unimarg', $data, $index, 0);

    // Precio de venta en dolares
    $u->UNIPUVD = returnValueUnidad('unipuvd', $data, $index, 0);
    // Precio de venta en soles
    $u->UNIPUVS = returnValueUnidad('unipuvs', $data, $index, 0);
    // Peso
    $peso = $producto->ProPeso;
    $u->UniPeso  = returnValueUnidad('unipeso', $data, $index, $peso);
    // Precio adicional
    $u->UniPAdi  = returnValueUnidad('unipadi', $data, $index, 0);;
    // Margen adicional
    $u->UniMarg1 = returnValueUnidad('unimarg1', $data, $index, 0);;
    // Precio de venta2 en soles
    $u->UniPUVS1 = returnValueUnidad('unipuvs1', $data, $index, 0);;
    // Precio de venta2 en dolar    
    $u->UniPUVD1 = returnValueUnidad('unipuvd1', $data, $index, 0);
    // Empcodi
    $u->empcodi  = empcodi();
    // 
    $u->save();
  }
}
