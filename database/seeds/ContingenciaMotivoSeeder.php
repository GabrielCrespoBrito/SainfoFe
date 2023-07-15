<?php

use App\Models\Contingencia\ContingenciaMotivo;
use Illuminate\Database\Seeder;

class ContingenciaMotivoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$datas = [
			['descripcion' => 'Conexión internet', 'cod_sunat' => '1'],
			['descripcion' => 'Fallas fluido eléctrico', 'cod_sunat' => '2'],
			['descripcion' => 'Desastres Naturales', 'cod_sunat' => '3'],
			['descripcion' => 'Robo', 'cod_sunat' => '4'],
			['descripcion' => 'Fallas en el sistema de emisión electrónica', 'cod_sunat' => '5'],
			['descripcion' => 'Ventas por emisores itinerantes', 'cod_sunat' => '6'],
			['descripcion' => 'Otros', 'cod_sunat' => '7'],
		];
		for ($i = 0; $i < count($datas); $i++) {
			ContingenciaMotivo::create($datas[$i]);
		}
	}
}
