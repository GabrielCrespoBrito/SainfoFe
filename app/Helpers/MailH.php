<?php 

namespace App\Helpers;

use App\ContrataEntidad;
use App\Mail\MailContrata;
use Illuminate\Support\Facades\Mail;

/**
 * 
 */
class MailH
{
	public static function contrata($id, $data ){

		ini_set('max_execution_time', 90);
		
		$cont = ContrataEntidad::find($id);
		$to = $data['to'];
		$fileHelper = fileHelper();
    $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled'=>'true']);
    $name = "Documento contrato";

    $dompdf->loadHtml(view('contratas.pdf',['contenido' => $cont->contenido, 'title' => $name ]));    
    $dompdf->render();
    // $dompdf->output()
    $path = $fileHelper->saveTemp( $dompdf->output() , "contrata.pdf" );

    $data = [
    	'nombre_remitente' => $cont->nameEntidad(),
      'subject'  => $data['subject'],      
      'to'     => $to,
      'from' => get_setting('sistema_email' , 'fonsecabwa@gmail.com'),      
      'name'     => get_setting('nombre' , 'Sainfo'),     
      'mensaje'  => $data['message'],
      'path' => $path
    ];

		return Mail::to($to)->send(new MailContrata($data));
	}

}

