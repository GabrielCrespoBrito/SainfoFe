<?php

namespace App\Jobs\Venta;

use App\GuiaSalida;
use App\Venta;
use App\Resumen;
use App\ResumenDetalle;
use App\TipoDocumentoPago;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class VentaSunatValidate
{
	public $mescodi;
	public $envioDirectoBoletas;
	public $data = [
		'success' => true,
		'docs' => []
	];

	public function __construct($mescodi)
	{
		$this->mescodi = $mescodi;
		$this->envioDirectoBoletas = get_empresa()->fe_envbole == "1";
	}

	public function getQueryVentas()
	{
		$tipos_documentos = [ Venta::FACTURA, Venta::NOTA_CREDITO, Venta::NOTA_DEBITO, Venta::BOLETA ];

		return Venta::where('MesCodi', $this->mescodi )
			->where('VtaFMail', StatusCode::ERROR_0011['code'] )
			->whereIn('TidCodi', $tipos_documentos  )
			->get();
	}

  public function getQueryGuias()
  {
    return GuiaSalida::where('mescodi', $this->mescodi)
      ->where('GuiEFor', '=', "1")
      ->where('fe_rpta', '=', "9")
      ->orderBy('GuiOper', 'desc')
      ->get();
  }

	public function getQueryResumenAnulacion()
	{
		return Resumen::where('MesCodi', $this->mescodi)
			->where('DocMotivo', 'A')
			->where('DocCEsta', '!=', '0')
			->get();
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->processVentas();
    $this->processGuias();
		$this->processResumen();
	}
  
	public function processGuias()
	{
		$docs = $this->getQueryGuias();

		foreach( $docs as $doc ) {
      $route = route('guia.pendientes', ['mes' =>  $doc->mescodi ]);
      $showRoute = route('guia.show', $doc->GuiOper );
      
      $docArr = $doc->toArray();
      $docArr['VtaOper'] = $docArr['GuiOper'];
      $docArr['VtaNume'] = $docArr['GuiSeri'] . '-' . $docArr['GuiNumee']; 
      $doc = (object) $docArr;
      $this->setErrorValidate( $doc, null, true, false , $route, $showRoute );
		}
	}

	public function processVentas()
	{
		$docs = $this->getQueryVentas();

		foreach( $docs as $doc ) {

      $showRoute = route('ventas.show',  $doc->VtaOper );

      if( $doc->isBoleta() && ! $this->envioDirectoBoletas ){
				$this->setErrorValidate( $doc, null, true, false , null ,  $showRoute );
				continue;
			}

			$doc->searchSunatGetStatus();

			if( $doc->isPendiente()  ){
				$this->setErrorValidate( $doc, null, true , false, null , $showRoute );
				continue;
			}

			if( $doc->isRechazado()  ){
				$this->setErrorValidate( $doc, null, true , true, '#' , $showRoute );
			}

		}
	}

	public function processResumen()
	{
		$resumenes = $this->getQueryResumenAnulacion();

		foreach( $resumenes as $resumen ) {
			$resumen_detalle = $resumen->items->first();
			$doc = $resumen_detalle->documento();

			if( $doc == null ){
				continue;
			}
			
			if( ! $doc->isAnulada() ){
				$doc->searchSunatGetStatus();
			}

			 if( ! $doc->isAnulada() ){
				$this->setErrorValidate( $doc, $resumen, false );
			 }

			$doc = $resumen->items->first()->documento();
		}
	}

	public function getData()
	{
		return $this->data;
	}

	public function setErrorValidate($doc, $resumen, $isVenta = true , $rechazado = false , $accionRoute = null, $showRoute = '#' )
	{
		if( $isVenta ){
			$text = $rechazado ? 
				'La documento %s se encuentra rechazado, contactar con el cliente para reemplazar el documento' : 
				'La documento %s se encuentra en estado pendiente';
			$message = sprintf( $text , $doc->VtaNume );
		}

		else {
			$message = sprintf('El documento %s se ha intentando anular, pero todavia no se ha efectuado correctamente la anulaciòn' , $doc->VtaNume );
		}

		$actionRoute = $accionRoute ?? route('ventas.accion_sunat', $doc->VtaOper);
		$actionMessage = 'Acciòn';

		$this->addError($doc, $message, $actionRoute, $actionMessage , $showRoute );
		$this->data[ 'success' ] = false;
	}


	public function addError( $doc, $message, $actionRoute, $actionMessage , $showRoute = '' )
	{
		$data_error = [
			'VtaOper'       => $doc->VtaOper,
			'document'      => $doc->VtaNume,
			'message'       => $message,
			'actionRoute'   => $actionRoute,
			'actionMessage' => $actionMessage,
      'showRoute' => $showRoute
		];

		array_push( $this->data['docs'] , $data_error );
	}

}