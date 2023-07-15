<?php

namespace App\Models\TomaInventario;

use App\Unidad;
use App\Producto;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TomaInventarioDetalle extends Model
{
  protected $table = "productos_det_mat_inventario";
  protected $primaryKey = "Item";
  public $timestamps = false;
  public $fillable = [
    "Id",
    "ProCodi",
    "proNomb",
    "proMarc",
    "UnpCodi",
    "ProStock",
    "ProInve",
    "ProPUCS",
  ];
  
  use UsesTenantConnection;


  public function tomaInventario ()
  {
    return $this->belongsTo( TomaInventario::class, 'InvCodi', 'InvCodi' );
  }

  public function getDiff()
  {
    return $this->ProInve - $this->ProStock;
  }

  public function getImporte()
  {
    return $this->ProInve * $this->ProPUCS;
  }

  public function isFromIngreso()
  {
    return $this->ProInve > $this->ProStock;
  }

  public function getUnidadCodigo()
  {
    return Unidad::where('Id', $this->Id)->first()->Unicodi;
  }

  public function producto()
  {
    return $this->belongsTo( Producto::class, 'ID', 'Id' );
  }

  public function getInfoJS()
  {
    return json_encode([
      'id' => $this->Id,
      'procodi' => $this->ProCodi,
      'nombre' => $this->proNomb,
      'marca' => $this->proMarc,
      'unidad' => $this->UnpCodi,
      's_actual' => $this->ProStock,
      'costo' => $this->ProPUCS,
    ]);
  }
}
