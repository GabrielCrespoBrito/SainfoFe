<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\ReporteEnvioDocumentosMail;


class ReporteDeEnvioDeDocumentosJob implements ShouldQueue
{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		/**
		 * Create a new job instance.
		 *
		 * @return void
		 */
		public $data;
		public $is_resumen;		

		public function __construct($data = "", $is_resumen = false)
		{
			$this->data = $data; 
			$this->is_resumen = $is_resumen; 			
		}

		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			ini_set('max_execution_time', 90);

			$emailToSend = get_setting('email_receptor', 'fonsecabwa@gmail.com');
			$tema = "Reporte del envio automatico de documentos a la sunat.";

			if( $this->is_resumen ){
				$tema = "Reporte del envio automatico de resumenes a la sunat";
			}

			$data = [
				'subject' => $tema,
				'para'    => $emailToSend,
				'name'    => "Soporte",     
				'fecha'   => hoy(),
				'mensaje' => "Informe detallado del envio de documentos automaticos de documentos de la sunat por parte del sistema",     
			];
			
			$data['empresas'] = $this->data;
			$data['is_resumen'] = $this->is_resumen;

			\Mail::to($emailToSend)->send(new ResumenEnvioGuiasMail($data));
		}
}
