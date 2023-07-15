<?php

namespace App\Models\Tienda;

use App\ClienteProveedor;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tienda\Utils\interactWithMetaData;

class Orden extends Model
{
	use interactWithMetaData;

	protected $connection = 'mysql_tienda';
	protected $table = 'wp_posts';
	protected $primaryKey = 'ID';
	const DATA_FORM = [
		'razon_social' => '_billing_company',
		'documento' => '_billing_first_name',
		'tlf' => '_billing_phone',
		'email' => 'ywraq_other_email_content',
	];

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('order', function ($query) {
			return $query->where('post_type', 'shop_order');
		});
	}


	public function getCotizacionId()
	{
		return $this->getInfoKeyValue(MetaData::COTIZACION_ID);
	}

	public function getFormClienteData()
	{
		return [
			'razon_social' =>	$this->getInfoKeyValue(self::DATA_FORM['razon_social']),
			'documento' => $this->getInfoKeyValue(self::DATA_FORM['documento']),
			'tlf' => $this->getInfoKeyValue(self::DATA_FORM['tlf']),
			'email' => $this->getInfoKeyValue(self::DATA_FORM['email'])
		];
		// 
	}


	/**
	 * Obtener el cliente del sistema sainfo, o si no devolver la información del cliente de la orden para registrar su información
	 *
	 * @return void
	 */
	public function getClienteSainfoOrData()
	{
		$data = $this->getFormClienteData();
		$clienteSainfo = $data['documento'] ? $this->clienteSainfo($data['documento']) : null;

		return [
			'exist' => $clienteSainfo != null,
			'data' => $data,
			'clienteSainfo' => $clienteSainfo
		];

	}


	public function clienteSainfo($documento)
	{
		return ClienteProveedor::findByRuc($documento, null, ClienteProveedor::TIPO_CLIENTE);
	}


	public function items()
	{
		return $this->hasMany(OrdenItem::class, 'order_id', 'ID');
	}

	public function stat()
	{
		return $this->hasOne(OrdenStat::class, 'order_id');
	}

	public function getOrdenWithNumeral($prefix = '')
	{
		return $prefix . '#' . $this->ID;
	}

	public function getProductsFormat()
	{
		$products = [];
		$i = 1;

		foreach( $this->items as $item ){

			$data = [
				'id' => $item->product_id,
				'item' => $i,
				'codigo' => $item->producto->code,
				'nombre' => $item->producto->nombre,
				'cantidad' => $item->product_qty,
			];
			array_push($products, $data);
			$i++;
		}

		return $products;
	}

	
	public function getFormatData()
	{
		$user = $this->stat->cliente->user;

		// Usuario
		$username = $user->user_login;
		$user_nicename = $user->user_nicename;
		$user_email = $user->user_email;
		// Cliente
		$razon_social = $this->getInfoKeyValue( '_billing_company' );
		$documento = $this->getInfoKeyValue( '_billing_first_name' );
		$email = $this->getInfoKeyValue( 'ywraq_customer_email' );
		$telefono = $this->getInfoKeyValue( '_billing_phone' );
		$mensaje = $this->getInfoKeyValue( 'ywraq_customer_message' );
		// Orden
		$id = $this->ID;
		$fecha = $this->post_date;
		$titulo = $this->post_name;
		$estatus = $this->stat->getStatusReadable();
		// Productos
		$productos = [];
		$cantidad = $this->stat->num_items_sold;

		return [
			'orden_nombre' => $this->getOrdenWithNumeral('Orden'),
			// Usuario
			'username' => $username,
			'user_nicename' => $user_nicename,
			'user_email' => $user_email,
			// Cliente
			'razon_social' => $razon_social,
			'documento' => $documento,
			'email' => $email,
			'telefono' => $telefono,
			'mensaje' => $mensaje,
			// Orden
			'id' => $id,
			'fecha' => $fecha,
			'titulo' => $titulo,
			'estatus' => $estatus,
			// Productos
			'productos' => $this->getProductsFormat() ,
			'total_cantidad' => $cantidad,
		];
	}

	public function saveCompleteStatus()
	{
		return $this->stat->updateStatus(OrdenStat::STATUS_COMPLETO);
	}

	public function isCompleteStatus()
	{
		return $this->stat->status == OrdenStat::STATUS_COMPLETO;
	}

	public function getTextReferencia()
	{
		return "COTIZACION QUE HACE REFERENCIA A LA SOLICITUD DE PRESUPUESTO " . $this->getOrdenWithNumeral();
	}

}
