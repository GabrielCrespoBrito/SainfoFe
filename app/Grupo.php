<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelHelper;
use App\Traits\ModelTrait;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Builder;

class Grupo extends Model
{
  use
    ModelTrait,
    UsesTenantConnection,
    ModelEmpresaScope;

  protected $table       = 'grupos';
  public $timestamps   = false;
  protected $primaryKey = 'GruCodi';
  const PRIMARY_KEY = 'GruCodi';
  const EMPCODI_FIELD = 'empcodi';
  const EMPRESA_CAMPO = "empcodi";
  protected $keyType = 'string';
  public $incrementing = false;
  public $fillable = ["GruCodi", "GruNomb", "GruEsta", 'empcodi'];
  const INIT = "01";


  public function scopeNoDeleted($query)
  {
    return $query->where('UDelete', '!=', '*');
  }

  public function deleteSoft()
  {
    if ($this->productos->count() || $this->fams->count()) {
      $this->deleteDb();
      return true;
    }

    $deleted = $this->delete();

    $ch = cacheHelper();

    $ch->forget('grupo.all');
  
    return $deleted;

  }

  public function productos()
  {
    return $this->hasMany(Producto::class, 'grucodi', 'GruCodi');
  }


  public static function last_id()
  {
    if ($codigo =  self::where('empcodi', empcodi())->get()->max('GruCodi')) {
      return agregar_ceros($codigo, 2, 1);
    }

    return self::INIT;
  }

  public function familias()
  {
    // return $this->hasMany(Familia::class, 'gruCodi', 'GruCodi')->where('empcodi', empcodi())->get();
    return $this->hasMany(Familia::class, 'gruCodi', 'GruCodi')->get();
  }

  public function fams()
  {
    return $this->hasMany(Familia::class, 'gruCodi', 'GruCodi');
  }

  public function familias_()
  {
    return
      Familia::where('gruCodi', $this->GruCodi)
      ->where('empcodi', $this->empcodi)
      ->get();
  }


  public function setGruNombAttribute($value)
  {
    $this->attributes['GruNomb'] = strtoupper($value);
  }

  public function ultimo_id()
  {
    return Familia::last_id($this->GruCodi, $this->empcodi);
  }


  public function delete_all()
  {
    foreach ($this->familias as $familia) {
      $famlia->delete();
    }
    $this->delete();
  }

  public function getIdAttribute()
  {
    return $this->GruCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->GruNomb;
  }


  public static function createDefault($empcodi)
  {
    return self::create([
      "GruCodi" => '00',
      "GruNomb" => 'SIN DEFINIR',
      "GruEsta" => '0',
      "empcodi" => $empcodi,
    ]);
  }

  public function getFamiliaLastId()
  {
    $last_id = $this->fams->max('famCodi');

    if (is_numeric($last_id)) {
      return math()->addCero(($last_id + 1), 2);
    }

    $last_id_numeric = -1;

    foreach ($this->fams as $familia) {

      $id = $familia->famCodi;

      if (!is_numeric($id)) {
        continue;
      }

      if ($id > $last_id_numeric) {
        $last_id_numeric = $id;
      }
    }

    if ($last_id_numeric === -1) {
      return '00';
    }

    return math()->addCero($last_id_numeric + 1, 2);
  }

  public function createFamiliaDefault($name = null, $famcodi = null)
  {
    return Familia::createDefault($this->empcodi, $this->GruCodi, $name, $famcodi);
  }
}
