<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaMail extends Model
{
	protected $table = "ventas_emails";
	protected $primaryKey = "DetItem";
	protected $keyType = "string";	
	public $timestamps = false;		
	const CREATED_AT = 'DetFecha';
	const UPDATED_AT = false;
	protected $fillable = [
		'EmpCodi',  
		'VtaOper',  
		'DetAsun',  
		'DetMens',  			
		'DetItem',  						
		'DetFecha',  						
		'UsuCodi',  
		'DetEmail', 
		'DetEsta',  
		'DetFechaD',
		'DetPDF', 	
		'DetXML', 
		'DetCDR',   	
	];

	public function usuario()
	{
		return $this->belongsTo( User::class, 'UsuCodi' , 'UsuCodi' );
	}

	public static function test()
	{
		$info = [
			'EmpCodi'  => '001',
			'VtaOper'  =>  '12343',
			'DetAsun'  => 'ASUNTO NUEVO',
			'DetMens'  => 'MENSAJE',			
			'DetItem'  => self::lastId(),						
			'DetFecha' => '2018-01-05 11:00:00',
			'UsuCodi'  => '01',
			'DetEmail' => 'emailpara@gmail.com',
			'DetEsta'  => "ENVIADO",
			'DetFechaD'=> NULL,			

		];
		self::createRegister($info);
	}

	public static function lastId()
	{
		$ultimo = self::orderByDesc('DetItem')->first();

		if( $ultimo ){
			$codigo = $ultimo->DetItem + 1;
			return $codigo < 10 ? "0" . $codigo : $codigo;
		}
		else {
			return "01";
		}
	}


	public static function createRegister($data)
	{
    set_timezone();
		
		$info = [
			'EmpCodi'  => $data['empresa_codi'],
			'VtaOper'  =>  $data['documento_codi'],
			'DetAsun'  => $data['subject'],
			'DetMens'  => isset($data['mensaje']) ? $data['mensaje'] : NULL,			
			'DetItem'  => self::lastId(),	
			'DetPDF'   => isset($data['pdf']) ? $data['pdf'] : 0,	
			'DetXML'   => isset($data['xml']) ? $data['xml'] : 0,
			'DetCDR'   => isset($data['cdr']) ? $data['cdr'] : 0,	
			'DetFecha' => date('Y-m-d m:h:s'),
			'UsuCodi'  => auth()->user()->usucodi,
			'DetEmail' => isset($data['para'])?$data['para']:'',
			'DetEsta'  => "ENVIADO",
			'DetFechaD'=> NULL,			
		];

		self::create($info);
	}


}
