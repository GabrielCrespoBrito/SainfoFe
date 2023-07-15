<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;

class OrdenItem extends Model
{
    protected $connection = 'mysql_tienda';
	protected $table = 'wp_wc_order_product_lookup';
    protected $primaryKey = 'order_item_id';
    
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

}
