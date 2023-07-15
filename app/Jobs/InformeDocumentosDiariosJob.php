<?php

namespace App\Jobs;

use App\Empresa;
use App\Http\Controllers\Util\Sunat\DocumentoStatus;
use App\Http\Controllers\Util\Sunat\ReporteDocumentoStatus;
use App\Jobs\SendEmailReporteSunat;
use App\Mail\ReporteSunatDocumentos;
use Faker\Provider\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class InformeDocumentosDiariosJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $fecha;
	public $empcodi;
	public $documentos;	


	public function __construct( $fecha , $empcodi  )
	{		
		// $fecha = "2019-03-23";
		$this->fecha = $fecha;
		$this->empcodi = $empcodi;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$empresa = Empresa::find($this->empcodi);

		$documentos_group = 
		\DB::table('ventas_cab')
		->join('opciones', 'opciones.EmpCodi', '=', 'ventas_cab.EmpCodi')
		->where('ventas_cab.VtaFvta', $this->fecha )
		->where('opciones.EmpCodi', $this->empcodi )		
		->whereIn('ventas_cab.TidCodi',  [ "01" ,"03", "07", "08" ])
		->select(
			'ventas_cab.VtaOper',
			'ventas_cab.TidCodi',
			'ventas_cab.VtaSeri',
			'ventas_cab.VtaNumee',
			'ventas_cab.fe_obse',
			'ventas_cab.VtaEsta',
			'ventas_cab.VtaFvta',
			'ventas_cab.fe_rpta')
		->get()
		->groupBy('TidCodi');

		// -----------------------------------------------------------				
		// |																												 |
		// |																											   |
		// |																												 |
		// -----------------------------------------------------------

		$documentVerify = new DocumentoStatus( 
			$empresa->EmpLin1 , 
			$empresa->FE_USUNAT , 
			$empresa->FE_UCLAVE );

		$reporter = new ReporteDocumentoStatus( 
			$this->fecha , 
			$empresa->EmpLin1 , 
			$empresa->EmpNomb );

		$i = 0;

		foreach( $documentos_group as $tidcodi => $documentos ){

			foreach( $documentos as $documento ){

				$documentVerify->loadDocument($documento);
				$data_document = $documentVerify->verifyStatus();
				$data = array_merge( $data_document , (array) $documento );
				$reporter->loadDataDocument( $data );
			}
		}

		$data_reporte =  $reporter->getData();

		$adata_reporte =  [
  "cant_documentos" => 3,
  "busqueda_exitosas" => 3,
  "encontrados_sunat" => 0,
  "documentos" =>  [
     [
      "numero_documento" => "003698",
      "tipo_documento" => "01",
      "serie_documento" => "F001",
      "estado_documento" => "A",
      "busqueda_exitosa" => true,
      'ticket' => '3242423423',
      "encontrado_sunat" => true,
      "data_sunat" => [
        "statusCode" => "0004",
        "statusMessage" => "La constancia existe",
        "description" => "La Factura numero F001-003698, ha sido aceptada",
        "rpta" => "0",
      ],
      "VtaOper" => "007361",
      "TidCodi" => "01",
      "VtaSeri" => "F001",
      "VtaNumee" => "003698",
      'fe_rpta' => '0',
      'VtaFvta' => '2019-03-23',      
      "fe_obse" => "201903401757204",
      "VtaEsta" => "A",
    ],
    [
      "numero_documento" => "004031",
      "tipo_documento" => "01",
      "serie_documento" => "F001",
      "estado_documento" => "V",
      "busqueda_exitosa" => true,
      'ticket' => null,
      "encontrado_sunat" => true,
      "data_sunat" =>  [
        "statusCode" => "0004",
        "statusMessage" => "La constancia existe",
        "description" => "La Factura numero F001-004031, ha sido aceptada",
        "rpta" => "0",
      ],
      "VtaOper" => "008052",
      "TidCodi" => "01",
      "VtaSeri" => "F001",
      "VtaNumee" => "004031",
      'fe_rpta' => '0',
      'VtaFvta' => '2019-03-23',
      "fe_obse" => "La Factura numero F001-004031, ha sido aceptada",
      "VtaEsta" => "V",
    ],
    [
      "numero_documento" => "004032",
      "tipo_documento" => "01",
      "serie_documento" => "F001",
      "estado_documento" => "V",
      "busqueda_exitosa" => true,
      'ticket' => '3242423423',
      "encontrado_sunat" => true,
      "data_sunat" =>  [
        "statusCode" => "0004",
        "statusMessage" => "La constancia existe",
        "description" => "La Factura numero F001-004032, ha sido aceptada",
        "rpta" => "0",
      ],
      "VtaOper" => "008053",
      "TidCodi" => "01",
      "VtaSeri" => "F001",
      "VtaNumee" => "004032",
      'fe_rpta' => '0',
      'VtaFvta' => '2019-03-23',

      "fe_obse" => "La Factura numero F001-004032, ha sido aceptada",
      "VtaEsta" => "V",
    ]
  ],
  "empresa" => [
    "ruc" => "20538667782",
    "nombre" => "COPY RAPID J & M S.A.C.",
  ],
  "fecha" => "2019-03-23"
];

	/*

	{
		data : [
			{ fecha : '2018-01-05' , data : {} }
			{ fecha : '2018-01-06' , data : {} }
			{ fecha : '2018-01-07' , data : {} }
			{ fecha : '2018-01-08' , data : {} }			
		],
	}

	*/

		$data = ['fecha' => $this->fecha , 'data' => $data_reporte];
		$data = json_encode($data);

    getTempPath( "data.json", $data );

		SendEmailReporteSunat::dispatch($data_reporte);
	}
}
