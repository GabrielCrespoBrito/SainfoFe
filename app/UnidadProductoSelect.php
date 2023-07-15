<?php

namespace App;

use App\Util\ModelUtil\ModelEmpresaScope;
use Illuminate\Database\Eloquent\Model;

class UnidadProductoSelect extends Model
{
  use 
  ModelEmpresaScope;

  protected $table       = 'unidad';  
  protected $primaryKey   = "UniCodi";  
  protected $keyType   = "string";
  protected $timetamps   = false;
  const EMPRESA_CAMPO = "empcodi";


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

}
