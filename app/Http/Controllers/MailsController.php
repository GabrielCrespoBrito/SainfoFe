<?php

namespace App\Http\Controllers;
use App\Cotizacion;
use App\Empresa;
use App\EnvHandler;
use App\Http\Requests\MailRedactadoRequest;
use App\Http\Requests\MailSendDocumentRequest;
use App\Mail\EnviarCotizacion;
use App\Mail\MailTest;
use App\Mail\EnviarDocumento;
use App\Venta;
use App\VentaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailsController extends Controller
{
	public function __construct()
	{
    // $this->middleware(['permission:email ventas'])->only(['enviar_radactado']);
    $this->middleware(p_midd('A_EMAIL', 'R_VENTA'))->only(['enviar_radactado']);
    
		ini_set('max_execution_time', 90);
	}

	public function enviar_documento( MailSendDocumentRequest $request )
	{
		ini_set('max_execution_time', 90);

		$venta = Venta::find($request->id_factura);
		$data = [
			'subject' => 'DOCUMENTO TRIBUTARIO ELECTRONICO',			
			'cliente_nombre' => $venta->cliente->PCNomb,
			'cliente_ruc' => $venta->cliente->PCRucc,
			'cliente_documento' => $venta->VtaNume,
			'documento_codi' => $venta->VtaOper,			
			'view' => "mails.enviar_documento",
			'name' => $venta->empresa->EmpNomb,						
			'para' => $venta->cliente->PCMail,									
			'empresa_codi' => $venta->EmpCodi,
			'tipo_documento' => $venta->tipo_documento->TidNomb,
			'moneda' => $venta->moneda_abbre(),
			'monto' => $venta->VtaImpo,
			'fecha' => $venta->VtaFvta,			
			'from_form' => true,
			'attach'=> [],
		];

		$documentos = ['pdf','xml','cdr'];

		foreach( $documentos as $doc ){      
			if($venta->hasDoc($doc)){
				$data['attach'][$doc] = $venta->getDocPath($doc);					
			}
		}

		// EnvHandler::setEmailData();
		//VentaMail::createRegister($data);
		
		return Mail::to($venta->cliente->PCMail)->send(new EnviarDocumento($data));
	}

	public function enviar_radactado( MailRedactadoRequest $request )
	{
		ini_set('max_execution_time', 90);

		$venta      = Venta::find($request->id_factura);		
		$correos    = explode( "," , $request->hasta);
		$empresa = get_empresa();
		$documentos = explode( "," , $request->documentos );
		$data = [
			'subject' => $request->asunto,			
			'documento_codi' => $venta->VtaOper,			
			'cliente_documento' => $venta->VtaNume,			
			'view' => "mails.enviar_redactado",			
			'pdf'  => (int) in_array('pdf', $documentos),			
			'xml'  => (int) in_array('xml', $documentos),			
			'cdr'  => (int) in_array('cdr', $documentos),			
			'para' => $request->hasta,						
			'tipo_documento' => $venta->tipo_documento->TidNomb,
			'moneda'=> $venta->moneda_abbre(),
			'monto' => $venta->VtaImpo,
			'fecha' => $venta->VtaFvta,
			'empresa' => $empresa,
			'from' => 'loquesea@gmail.com',
			'empresa_codi' => $venta->EmpCodi,			
			'mensaje'      => $request->mensaje ?? "",						
			'from_form'    => true,
			'name'         => $venta->empresa->EmpNomb,			
			'attach'		   => [],
		];

    $empresa_ruc = $empresa->EmpLin1;
    $fileHelper = fileHelper($empresa_ruc);

		if( $request->documentos != null ){
			foreach( $documentos as $doc ){

				// dump($doc);

				if($venta->hasDoc($doc)){
          $name = "";
          if( $doc === 'zip' ){
            $name =  $venta->nameFile('.zip');
            $content = $fileHelper->getEnvio($name);
          }
          if( $doc === 'pdf' ){
            $name = $venta->nameFile('.pdf');
            $content = $fileHelper->getPdf($name);
          }
          if( $doc === 'cdr' ){
            $name = $venta->nameCdr('.zip');
            $content = $fileHelper->getCdr($name);
          }
          $path = $fileHelper->saveTemp( $content , $name );      
          $data['attach'][$doc] = $path;          
				}
			}
		}

		$venta->VtaMail = 1;
		$venta->save();

		$r = 
		Mail::to($correos)
		// ->from()
		// ->
		->send(new EnviarDocumento($data));
		
		//VentaMail::createRegister($data);
		return $r;
	}

	public function mails_enviados( Request $request )
	{
		ini_set('max_execution_time', 90);

    if( isset($request->id_cotizacion)  ){

      $c = Cotizacion::find($request->id_cotizacion);

      $data = [
        'mail' => $c->cliente->PCMail
      ];

      return response()->json( $data );
    }

    else {

  		$documento = Venta::with(['cliente_with' => function($q){
  			$q->where('EmpCodi', empcodi());
			}])->where('VtaOper', $request->id_factura)
			->where('EmpCodi', empcodi())->first();    
			  
  		$mails = $documento->mails;
  		$mails_data = [];

  		foreach ($mails as $mail) {
  			$d = $mail->toArray();
  			$d['user'] = $mail->usuario;
  			array_push($mails_data, $d);
  		}

  		return [ 'documento' => $documento , 'mails' => $mails_data];     
    }
	}

  public function cotizacion_redactada(Request $request)
  {
		ini_set('max_execution_time', 90);

    $cotizacion = Cotizacion::find( $request->id_cotizacion );   
    $data = $cotizacion->dataPdf();       
    $correos = explode( "," , $request->hasta );
		$path_pdf = $cotizacion->savePdf();
		$cab_message = "hola";

    $data = [
			'cab_message' => $cab_message,
      'subject' => $request->asunto,      
      'para' => $request->hasta,
      'name' => $cotizacion->empresa->EmpNomb,     
      'mensaje' => $request->mensaje ?? "",     
      'cotizacion' => $cotizacion,
      'pdf' => $path_pdf
    ];

    return Mail::to($correos)->send(new EnviarCotizacion($data));

  }

	public function envio_email()
	{
		ini_set('max_execution_time', 90);
		$emailToSend = "gabrielc1990poker@gmail.com";

    $data = [
      'subject'  => "Email de prueba",      
      'para'     => $emailToSend,
      'name'     => "Gabriel Crespo",     
      'mensaje'  => "Mensaje generado por el sistema sainfo",     
    ];

    // EnvHandler::setEmailData();    
		Mail::to($emailToSend)->send(new MailTest($data));
		notificacion('Email Enviado' , "" , "success");
		return redirect()->route('home');
	}

}
	

