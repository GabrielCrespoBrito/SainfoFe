<?php

namespace App\Models\Suscripcion;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Support\Facades\Cache;

class Plan extends Model
{
  use UsesSystemConnection;

  const KEY_CACHE_PAGE = "PLANES.LANDING";

  protected $table = "suscripcion_system_planes";

  public function duraciones()
  {
    return $this->hasMany(PlanDuracion::class, 'plan_id');
  }

  public function isDemo()
  {
    return $this->is_demo == "1";
  }

  public function isPro()
  {
    return $this->is_pro == "1";
  }

  public static function getDemo()
  {
    return self::where('is_demo', 1)->first();
  }

  public static function getFormatLanding()
  {
    return Cache::rememberForever( self::KEY_CACHE_PAGE, function(){
    $planes = self::all();
    $data = [];
    foreach( $planes as $plan ){
      $caracteristicas_data = [];
      $caracteristicas = $plan->duraciones->first()->caracteristicas;
      foreach ($caracteristicas as $caracteristica ) {
        $c_data = (object) [
          'nombre' => $caracteristica->caracteristica->nombre,
          'value' => $caracteristica->value,
          'message' => $caracteristica->caracteristica->adicional,
        ];
        array_push( $caracteristicas_data , $c_data );
      }

      $datas_values = (object) [
        'nombre' => $plan->nombre,
        'is_demo' => (int) $plan->isDemo(),
        'id' => $plan->id,
        'caracteristicas' => $caracteristicas_data,
      ];
      array_push( $data, $datas_values );
    }
    return $data;
    });
  }
}
