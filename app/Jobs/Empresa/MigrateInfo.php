<?php

namespace App\Jobs\Empresa;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateInfo
{
	use Dispatchable;
	protected $connexionDbExport = "mysql_export";
	protected $connexionDbImport = "mysql_import";
	public $empcodi;
	protected $empresa;
	protected $tables = [
		'anio',
		'bancos_cuenta_cte',
		'caja',
		'caja_detalle',
		'compras_cab',
		'compras_detalle',
		'compras_pago',
		'condicion',
		'condicion_cpra_vta',
		'contingencias_detalles',
		'cotizaciones',
		'cotizaciones_detalle',
		'documentos_pendientes',
		'documentos_pendientes_detalle',
		'egresos',
		'empresa_transporte',
		'familias',
		'grupos',
		'guia_detalle',
		'guias_cab',
		'ingresos',
		'lista_precio',
		'local',
		'marca',
		'moneda',
		'productos',
		'prov_clientes',
		'tcmoneda',
		'unidad',
		'vendedores',
		'ventas_cab',
		'ventas_detalle',
		'ventas_emails',
		'ventas_pago',
		'ventas_ra_cab',
		'ventas_ra_detalle',
	];

	public function __construct($empresa)
	{
		ini_set('memory_limit', '1024M'); // or you could use 1G
		$this->empresa = $empresa;
		$this->empcodi = $empresa->empcodi;
	}


	public function getEmpresa()
	{
		return $this->empresa;
	}

	public function setEmpresa($empresa)
	{
		$this->empresa = $empresa;

		return $this;
	}

	public function getTables()
	{
		return $this->tables;
	}

	public function modifyCustomConnection()
	{
		// EnvHandler::changeEnv([
		// 	'DB_DATABASE_CUSTOM' => $this->getEmpresa()->getDatabase()
		// ]);
	}

	public function insertInDatabase($table, $data)
	{
		$dataFormat = (array) $data;

		if( array_key_exists('empcodi', $data )  ){
			$dataFormat['empcodi'] = $this->empcodi;
		}
		elseif( array_key_exists('EmpCodi', $data )  ){
			$dataFormat['EmpCodi'] = $this->empcodi;
		}
		
		//
		if( array_key_exists('TipoIGV', $data )  ){
			// delete
			unset($dataFormat['TipoIGV']);
		}


		try {
			DB::transaction(function() use( $table , $dataFormat ){
				DB::connection( $this->getConnexionDbImport() )
				->table( $table )
				->insert( $dataFormat );
			});
		} catch (\Throwable $th) {
		}
	}

	public function tableHasEmpCodi($table)
	{
		return 
			Schema::connection(	$this->getConnexionDbExport()	)->hasColumn($table, 'empcodi') ||
			Schema::connection(	$this->getConnexionDbExport()	)->hasColumn($table, 'EmpCodi');
	}

	public function obtainData($table)
	{
		
		$query = DB::connection( $this->getConnexionDbExport() )->table($table);
		
		if ($this->tableHasEmpCodi($table)) {
			$tableFilter = $table . '.empcodi';
			$query->where( $tableFilter , '=',  "001" );
		}
		
		$datas = $query->get();

		return $datas;
	}

	public function handle()
	{
		$this->modifyCustomConnection();


		foreach ( $this->getTables() as $table) {

			
			// if( 
			// $table != 'ventas_detalle' 
			// $table != 'ventas_emails' 
			// $table != 'ventas_pago' 
			// $table != 'ventas_ra_cab'
			// $table != 'ventas_ra_detalle' 			
			// ){
				// continue;
			// }
			

			$datas = $this->obtainData($table);
			
			// dump(  $table, $datas->count());

			foreach ($datas as $data) {
				$this->insertInDatabase($table, $data);
			}
		}
	}

	/**
	 * Get the value of connexionDbExport
	 */ 
	public function getConnexionDbExport()
	{
		return $this->connexionDbExport;
	}

	/**
	 * Get the value of connexionDbImport
	 */ 
	public function getConnexionDbImport()
	{
		return $this->connexionDbImport;
	}
}
