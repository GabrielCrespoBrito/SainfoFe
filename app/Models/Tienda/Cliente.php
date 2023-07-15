<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  protected $connection = 'mysql_tienda';
	protected $table = 'wp_wc_customer_lookup';
	protected $primaryKey = 'customer_id';

	public function user()
	{
		return $this->belongsTo( User::class, 'user_id' ,'ID' );
	}


}