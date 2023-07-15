<?php

use App\Detraccion;
use Illuminate\Database\Seeder;

class DetraccionArticuloSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$data = [
			['cod_sunat' => '001', 'active' => 1, 'descripcion' => 'Azúcar', 'porcentaje' => '10'],  // ok
			['cod_sunat' => '003', 'active' => 1, 'descripcion' => 'Alcohol etílico', 'porcentaje' => '10'], // ok
			['cod_sunat' => '004', 'active' => 0, 'descripcion' => 'Recursos hidrobiológicos', 'porcentaje' => '4'], // hay que poner otros tags
			['cod_sunat' => '005', 'active' => 1, 'descripcion' => 'Maíz amarillo duro', 'porcentaje' => '4'],// ok
			['cod_sunat' => '006', 'active' => 1, 'descripcion' => 'Algodón', 'porcentaje' => '11'], // 3033
			['cod_sunat' => '007', 'active' => 1, 'descripcion' => 'Caña de azúcar', 'porcentaje' => '10'], // OK
			['cod_sunat' => '008', 'active' => 1, 'descripcion' => 'Madera', 'porcentaje' => '4'], // OK
			['cod_sunat' => '009', 'active' => 1, 'descripcion' => 'Arena y piedra.', 'porcentaje' => '10'], // OK
			['cod_sunat' => '010', 'active' => 1, 'descripcion' => 'Residuos, subproductos, desechos, recortes y desperdicios', 'porcentaje' => '15'], // OK
			['cod_sunat' => '011', 'active' => 1, 'descripcion' => 'Bienes del inciso A) del Apéndice I de la Ley del IGV', 'porcentaje' => '9'],
			['cod_sunat' => '012', 'active' => 1, 'descripcion' => 'Intermediación laboral y tercerización', 'porcentaje' => '12'],
			['cod_sunat' => '013', 'active' => 1, 'descripcion' => 'Animales vivos', 'porcentaje' => '10'],
			['cod_sunat' => '014', 'active' => 1, 'descripcion' => 'Carnes y despojos comestibles', 'porcentaje' => '4'],
			['cod_sunat' => '015', 'active' => 1, 'descripcion' => 'Abonos, cueros y pieles de origen animal', 'porcentaje' => '10'],
			['cod_sunat' => '016', 'active' => 1, 'descripcion' => 'Aceite de pescado', 'porcentaje' => '10'],
			['cod_sunat' => '017', 'active' => 1, 'descripcion' => 'Harina, polvo y “pellets” de pescado, crustáceos, moluscos y demás invertebrados acuáticos', 'porcentaje' => '9'],
			['cod_sunat' => '018', 'active' => 0, 'descripcion' => 'Embarcaciones pesqueras', 'porcentaje' => '9'], // 3033
			['cod_sunat' => '019', 'active' => 1, 'descripcion' => 'Arrendamiento de bienes muebles', 'porcentaje' => '10'],
			['cod_sunat' => '020', 'active' => 1, 'descripcion' => 'Mantenimiento y reparación de bienes muebles', 'porcentaje' => '12'], // OK
			['cod_sunat' => '021', 'active' => 1, 'descripcion' => 'Movimiento de carga', 'porcentaje' => '10'], // OK
			['cod_sunat' => '022', 'active' => 1, 'descripcion' => 'Otros servicios empresariales', 'porcentaje' => '12'],
			['cod_sunat' => '023', 'active' => 1, 'descripcion' => 'Leche', 'porcentaje' => '4'],
			['cod_sunat' => '024', 'active' => 1, 'descripcion' => 'Comisión mercantil', 'porcentaje' => '10'],
			['cod_sunat' => '025', 'active' => 1, 'descripcion' => 'Fabricación de bienes por encargo', 'porcentaje' => '10'],
			['cod_sunat' => '026', 'active' => 1, 'descripcion' => 'Servicio de transporte de personas', 'porcentaje' => '10'],
			['cod_sunat' => '029', 'active' => 1, 'descripcion' => 'Algodón en rama sin desmontar', 'porcentaje' => '11'],
			['cod_sunat' => '030', 'active' => 1, 'descripcion' => 'Contratos de construcción', 'porcentaje' => '4'],
			['cod_sunat' => '031', 'active' => 1, 'descripcion' => 'Oro gravado con el IGV', 'porcentaje' => '10'], // OK
			['cod_sunat' => '032', 'active' => 1, 'descripcion' => 'Páprika y otros frutos de los géneros capsicum o pimienta', 'porcentaje' => '10'],
		];

		for ($i = 0; $i < count($data); $i++) {
			Detraccion::unguard();
			Detraccion::create($data[$i]);
		}
	}
}
