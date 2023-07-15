<?php

namespace App\Mail;

use App\EnvHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReporteSunatDocumentos extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */

	public $data;

	public function __construct( $data )
	{
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$data = $this->data;
		EnvHandler::setEmailDataManual(
			get_setting('host_mail' , 'smtp.gmail.com'),
			get_setting('mail_port' , '587'),
			get_setting('mail_encriptacion' , 'tls'),           
			get_setting('sistema_email' , 'gabrielc1990poker@gmail.com'),
			get_setting('sistema_password ' , 'Fonblas12'),
			get_setting('driver_email' , 'smtp')
		);

		$empresa_ruc = $this->data['empresa']['ruc'];
		// $empresa_nombre = $this->data['empresa']['nombre'];

		// $subject = "RD ({$data['fecha']}) ({$empresa_ruc}) ({$empresa_nombre]})";
		$subject = "RD ({$data['fecha']}) ({$data['empresa']['ruc']}) ({$data['empresa']['nombre']})";

		return $this
		->subject( $subject )
		->from( get_setting('sistema_email', 'fonsecabwa@gmail.com') , "Sainfo" )
		->view('mails.busqueda_sunat' , ['data' => $this->data ] );

	}
}
