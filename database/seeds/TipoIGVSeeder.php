<?php

use App\Venta;
use App\TipoIgv;
use Illuminate\Database\Seeder;

class TipoIGVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'cod_sunat' => '10' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Operación Onerosa', 'gratuito_disponible' => 0 ],
            [ 'cod_sunat' => '11' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Retiro por premio'],
            [ 'cod_sunat' => '12' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Retiro por donación'],
            [ 'cod_sunat' => '13' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Retiro'],
            [ 'cod_sunat' => '14' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Retiro por publicidad'],
            [ 'cod_sunat' => '15' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Bonificaciones'],
            [ 'cod_sunat' => '16' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - Retiro por entrega a trabajadores'],
            [ 'cod_sunat' => '17' , 'tipo' => Venta::GRAVADA, 'descripcion'  => 'Gravado - IVAP'],
            [ 'cod_sunat' => '20' , 'tipo' => Venta::EXONERADA, 'descripcion'  => 'Exonerado - Operación Onerosa', 'gratuito_disponible' => 0 ],
            [ 'cod_sunat' => '21' , 'tipo' => Venta::EXONERADA, 'descripcion'  => 'Exonerado - Transferencia Gratuita'],
            [ 'cod_sunat' => '30' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Operación Onerosa', 'gratuito_disponible' => 0 ],
            [ 'cod_sunat' => '31' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro por Bonificación'],
            [ 'cod_sunat' => '32' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro'],
            [ 'cod_sunat' => '33' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro por Muestras Médicas'],
            [ 'cod_sunat' => '34' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro por Convenio Colectivo'],
            [ 'cod_sunat' => '35' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro por premio'],
            [ 'cod_sunat' => '36' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Inafecto - Retiro por publicidad'],
            [ 'cod_sunat' => '40' , 'tipo' => Venta::INAFECTA, 'descripcion'  => 'Exportación'],
        ];

        for ($i=0; $i < count($data) ; $i++) {
            TipoIgv::unguard();
            TipoIgv::create($data[$i]);
        }
    }
}
