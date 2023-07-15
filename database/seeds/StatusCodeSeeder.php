<?php

use Illuminate\Database\Seeder;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class StatusCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [

            [ 'tipo' => 'EXITO'	, 'status_code' => '0001',	'status_message' => 'El comprobante existe y está aceptado'],
            [ 'tipo' => 'EXITO'	, 'status_code' => '0002',	'status_message' => 'El comprobante existe  pero está rechazado'],
            [ 'tipo' => 'EXITO'	, 'status_code' => '0003',	'status_message' => 'El comprobante existe pero está de baja'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0004',	'status_message' => 'Formato de RUC no es válido (debe de contener 11 caracteres ,numéricos)'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0005',	'status_message' => 'Formato del tipo de comprobante no es válido (debe de contener ,2 caracteres)'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0006',	'status_message' => 'Formato de serie inválido (debe de contener 4 caracteres)'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0007',	'status_message' => 'El numero de comprobante debe de ser mayor que cero'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0008',	'status_message' => 'El número de RUC no está inscrito en los registros de la SUNAT'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0009',	'status_message' => 'EL tipo de comprobante debe de ser (01, 07 o 08)'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0010',	'status_message' => 'Sólo se puede consultar facturas, notas de crédito y debito ,electrónicas, cuya serie empieza con "F'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0011',	'status_message' => 'El comprobante de pago electrónico no existe'],
            [ 'tipo' => 'ERROR'	, 'status_code' => '0012',	'status_message' => 'El comprobante de pago electrónico no le pertenece'],

            ['tipo' => 'ERROR', 'status_code' => '9999',    'status_message' => 'No se pudo realizar la busqueda en la sunat'],


        ];

        foreach ($datas as $data) {
            StatusCode::create($data);
        }

            
    }
}
