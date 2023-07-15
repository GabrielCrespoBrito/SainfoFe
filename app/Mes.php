<?php

namespace App;

use Carbon\Carbon;
use App\Models\Cierre;
use App\Repositories\MesRepository;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
  protected $connection = "mysql";
  protected $table = "mes";
  public $timestamps = false;
  public $primaryKey = "mescodi";
  public $fillable = ['mescodi','mesnomb'];

  public static function boot()
  {
    parent::boot();

    static::creating(function (Mes $mes) {
      $mes->mesnomb ?? $mes->fillCompleteInfo($mes->mescodi);
      $mes->repository()->clearCache('all');
    });
  }

  /**
   * Establecer la informaciòn completa al modelo a partir del codigo
   * 
   * @param $mescodi = 202105 = Mayo del 2021
   * 
   * @return void
   */

  public function fillCompleteInfo($mescodi)
  {
    $year = substr($mescodi, 0, 4);
    $month =  substr($mescodi, -2);

    $date =  "{$year}-{$month}-01";
    $dateCarbon = new Carbon($date);
    $meses_nombres = get_meses();

    // MAYO DEL 2021 
    $mesnomb = 
    sprintf('%s DEL %s', 
      $meses_nombres[ (int) $month ],
      $year
    );

    $this->mesnomb = strtoupper($mesnomb);
    $this->mesdesd = $dateCarbon->firstOfMonth()->format('Y-m-d');
    $this->meshast = $dateCarbon->lastOfMonth ()->format('Y-m-d');
  }

  

  public function actual()
  {
    return $this->mescodi == date('Ym');
  }

  /**
   * Obtener el mescodi anterior partiendo de un mescodi especifico
   * @example 201908 = 201907
   * @example 201912 = 201812
   * 
   * @return string
   */
  public static function getMesAnterior($mescodi)
  {
    $data = self::getData($mescodi);

    # Año y mes
    $year = $data['year'];
    $mes  = $data['mes'];

    # Si es enero reducimos un año y el mes anterior sera diciembre (12)
    if ($mes == 1) {
      --$year;
      $mes = 12;
    } 
    else {
      --$mes;
    }
    
    return $year . math()->baseCero($mes);
  }

  public function repository()
  {
    return new MesRepository($this);
  }

  public static function getData($mescodi)
  {
    return [
      'year' => (int) substr($mescodi, 0, 4),
      'mes'  => (int) substr($mescodi, 4),
  ];
}

  public static function getNombre($mescodi)
  {
    if( $mes = Mes::find($mescodi)){
      return $mes->mesnomb;
    }

    $data = self::getData($mescodi);

    $mesesNombre = get_meses();
    $mes =   (int) $data['mes'];
    $mesNombre = $mesesNombre[$mes];

    return strtoupper($mesNombre . ' DEL ' . $data['year']);
  }

  public function cierre()
  {
    return $this->belongsTo( Cierre::class, 'mescodi', 'mescodi' )->withDefault();
  }

  public function getEstado()
  {
    return $this->cierre->getEstado();
  }

  public function toggleCierre()
  {
    $cierre = $this->cierre;

    if($cierre->exists){
      $cierre->delete();
    }

    else {
      Cierre::createByMescodi($this->mescodi);
    }

  }

}