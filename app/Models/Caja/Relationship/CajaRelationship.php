<?php 

namespace App\Models\Caja\Relationship;

use App\Mes;
use App\Venta;
use App\Compra;
use App\Empresa;
use App\BancoEmpresa;

trait CajaRelationship
{
  public function mes()
  {
    return $this->belongsTo( Mes::class, 'MesCodi', 'mescodi');
  }

  public function banco()
  {
    return $this->belongsTo(BancoEmpresa::class, 'CueCodi', 'CueCodi');
  }

  public function empresa()
  {
    return $this->belongsTo(Empresa::class, 'EmpCodi', 'EmpCodi');
  }

  public function ventas()
  {
    return $this->hasMany(Venta::class, 'CajNume', 'CajNume');
  }

  public function compras()
  {
    return $this->hasMany(Compra::class, 'CajNume', 'CajNume');
  }  


}