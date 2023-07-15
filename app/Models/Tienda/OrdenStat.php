<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;

class OrdenStat extends Model
{
	protected $connection = 'mysql_tienda';
	protected $table = 'wp_wc_order_stats';
	protected  $primaryKey = "order_id";
	public $timestamps = false;
	public $fillable = ["status"];
	const STATUS_NUEVO = 'wc-ywraq-new';
	const STATUS_COMPLETO = 'wc-completed';
	// const STATUS_UNDEFINED = 'wc-defined';

	const STATUS_ARR = [
		self::STATUS_NUEVO => 'Nuevo',
		self::STATUS_COMPLETO => 'Completo',
		// self::STATUS_UNDEFINED => 'indeterminado'
	];


	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'customer_id');
	}

	public static function getStatus($plural = true)
	{
		return self::STATUS_ARR;
	}

	public function getStatusReadable()
	{
		return self::STATUS_ARR[$this->status];
	}

	public function updateStatus($newStatus)
	{
		$this->update(['status' => $newStatus ]);
	}


	public function getStatusHtml()
	{
		switch ($this->status) {
			case self::STATUS_COMPLETO:
				return  "<span class='btn btn-xs btn-success'> <span class='fa fa-check'></span> " .
					self::STATUS_ARR[self::STATUS_COMPLETO] .
					"</span>";
				break;

			case self::STATUS_NUEVO:
				return  "<span class='btn btn-xs btn-warning'> <span class='fa fa-spin fa-spinner'></span> " .
					self::STATUS_ARR[self::STATUS_NUEVO] .
					"</span>";
				break;

			default:
				return  "<span class='btn btn-xs btn-info'>{$this->status}</span>";
				break;
		}
	}
}
