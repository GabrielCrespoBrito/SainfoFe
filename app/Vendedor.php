<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\VendedorRepository;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Vendedor extends Model
{

	protected $table = "vendedores";
	protected $primaryKey = "Vencodi";
	protected $keyType = "string";	
	public $timestamps = false;  
	const EMPRESA_CAMPO = "empcodi";
	protected $fillable = ["empcodi" , 'Vencodi' , 'vennomb' , 'vendire' , 'ventel1' , 'venmail' , 'usucodi', 'defecto' ];

	use UsesTenantConnection;

   protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('empresa', function ($query) {
      return $query->where( 'empcodi' , empcodi() );
    });


    static::addGlobalScope('noEliminados', function ($query) {
      return $query->where('UDelete', '=', '0');
    });

    
  }  

  public function isDelete()
  {
    return $this->UDelete == "*";
  }

	public function isDefault(){
		return $this->defecto == 1;
	}

	public static function createDefault($empcodi)
	{
		self::create( [
			'Vencodi' => 'OFIC',
			'vennomb' => 'OFICINA',
			'empcodi' => $empcodi,
			'defecto' => 1,
		]);
	}

	public function repository()
	{
		return new VendedorRepository($this,empcodi());
	}

	public function ventas()
	{
		return $this->hasMany( Venta::class, 'Vencodi', 'Vencodi' );
	}

	public function compras()
	{
		return $this->hasMany(Venta::class, 'vencodi', 'Vencodi');
	}

	public function guias()
	{
		return $this->hasMany(Venta::class, 'vencodi', 'Vencodi');
	}

  public function usuario()
  {
    return $this->belongsTo(User::class, 'usucodi', 'usucodi' )->withDefault();
  }


  public function  getUserLogin()
  {
    return $this->usuario->usulogi;
  }

  public function isUserLoginVendedor()
  {
    return auth()->user()->usucodi == $this->usucodi;
  }

  public function isInUse()
  {
    if ($this->ventas->count()) {
      return true;
    }

    if ($this->compras->count()) {
      return true;
    }

    if ($this->guias->count()) {
      return true;
    }

    return false;
  }


}