<?php

namespace App\Http\Controllers\ClienteAdministracion\Traits;
use App\Venta;
use Exception;

trait ExcellVentasExport {

  public function generateExcell( $ventas ){

    if( is_array($ventas) ){
      $ventas = Venta::with(['cliente_with', 'moneda'])->whereIn('VtaOper', $ventas)
      ->get()
      ->toArray(); 
      // throw new Exception("Error Processing Request", 1);
    }

    // _dd( $ventas );
    // exit();

    $nameFile = get_empresa()->EmpLin1 . '_' . date('Ymd');;
    $nameFileWithExt = ($nameFile . '.xlsx' );
    $pathTemp = public_path('temp\\');
    $pathFile = $pathTemp . $nameFileWithExt ;


    $excel = \Excel::create($nameFile , function($excel) use($ventas) {
      // Set the title
      $excel->setTitle('Sainfo - importación');
      // Chain the setters
      $excel
      ->setCreator('Sainfo EPR')
      ->setCompany('Sainfo EPR');      

      $excel->sheet('SAINFO ERP - VENTAS', function($sheet) use($ventas) {
    
        $headers = [
            'FECHA EMISIÓN', 'TIPO DOCUMENTO', 'SERIE', 'NUMERO', 'TIP DOC. DEL CLIENTE', 'NUMERO DOC. DEL CLIENTE' , 'RAZÓN SOCIAL' , 'MONEDA', 'T/C', 'GRAVADA', 'EXONERADA', 'INAFECTA', 'ISC', 'IGV', 'OTROS TRIBUTOS', 'TOTAL', 'ANULADO'
        ];

        $headers_letras = [
          'A', 'B', 'C', 'D', 'E', 'F' , 'G' , 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'
      ];

        $sheet
        ->setStyle([
          'font' => [ 'name' => 'Calibri' ],
          'alignment' => ['horizontal' => "center" ]
        ]);
       

        $sheet->row(1, function($row) use($headers) {

         });
      

        $sheet->row(1, $headers );


        foreach( $headers_letras as $letra ){
          $sheet->cell( $letra . 1 , function($cell){
            $cell->setBackground('#eff0f1');
            $cell->setFontWeight();
            $cell->setAlignment("center");
          });
        }

        // _dd( $ventas );
        // exit();

        foreach($ventas as $index => $venta) {
 
          $sheet->row($index+2, [
              $venta['VtaFvta'], 
              $venta['TidCodi'], 
              $venta['VtaSeri'], 
              $venta['VtaNumee'], 
              $venta['cliente_with']['TDocCodi'], 
              $venta['cliente_with']['PCRucc'], 
              $venta['cliente_with']['PCNomb'], 
              $venta['moneda']['monnomb'], 
              $venta['VtaTcam'], 
              $venta['Vtabase'], //gravada
              $venta['VtaExon'], 
              $venta['VtaInaf'], 
              $venta['VtaISC'], 
              $venta['VtaIGVV'],
              0, 
              $venta['VtaImpo'], 
              $venta['VtaEsta'], 
          ]); 
        }


      });

    })->store('xlsx', $pathTemp );

    $name_comprimido = $nameFile . '.zip';
    $path_comprimido = public_path('temp\\') . $name_comprimido ;
    $zipper = \Zipper::make( $path_comprimido );
    $zipper->add( $pathFile );
    $zipper->close();

    return [ 'path' => $path_comprimido , 'name' => $name_comprimido ];

  }



}
