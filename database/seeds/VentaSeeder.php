<?php

use Illuminate\Database\Seeder;
use App\Venta;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      \DB::table('ventas_cab')->truncate();

      // factory( App\Venta::class , 500 )
      for( $i = 0; $i < 80; $i++ ){

        $correlativo = Venta::UltimoCorrelativo( "F001" , "01");
        $serie = "F001";

        $data = [
          'VtaOper' => Venta::UltimoId(),
          'EmpCodi' => "001",
          'PanAno'  => "2018",
          'PanPeri' => date('m'),
          'TidCodi' => "01",
          'VtaSeri' => $serie,
          'VtaNumee' => $correlativo,
          'VtaNume' => $serie . "-" . "000001",
          'VtaFvta' => date('Y-m-d'),
          'VtaFVen' => date('Y-m-d'),
          'PCCodi'  => "00001",
          'ConCodi' => "01",
          'MonCodi' => "01",
          'VtaImpo' => random_int(5,2152),
          'VtaPago' => random_int(5,2152),
          'VtaBase' => random_int(5,2157),
          'VtaSald' => random_int(5,2157),
          'Vencodi' => "1OFI",
          'DocRefe' => "1234",
          'VtaTcam' => "01",
          'Vtacant' => random_int(1, 10),
          'VtaIGVV' => "18"
        ];

        Venta::create($data);        
      }



    }
}
