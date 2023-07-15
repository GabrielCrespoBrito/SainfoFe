<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tienda\Utils\interactWithMetaData;
use App\Producto as ProductoSainfo;

class Producto extends Model
{
	use interactWithMetaData;

	protected $connection = 'mysql_tienda';
	protected $table = 'wp_posts';
	protected $primaryKey = 'ID';

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('producto', function ($query) {
			return $query->where('post_type', 'product');
		});
	}

	public function productoSainfo()
	{
		return ProductoSainfo::findByProCodi( $this->code );
	}


	public function getCodeAttribute()
	{
		return $this->getInfoKeyValue('_sku');
	}

	public function getNombreAttribute()
	{
		return $this->post_title;
	}
}
