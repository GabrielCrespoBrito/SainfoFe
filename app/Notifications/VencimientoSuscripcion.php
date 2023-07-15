<?php

namespace App\Notifications;

use App\Empresa;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VencimientoSuscripcion extends Notification
{
	const POR_VENCER = "por_vencer";
	const VENCIDA = "vencida";

	use Queueable;
	public $empresa;
	public $tipo;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(Empresa $empresa, $tipo)
	{
		$this->empresa = $empresa;
		$this->tipo = $tipo;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}


	public function getAsunto()
	{
		switch ($this->tipo) {

			case self::POR_VENCER:
				return "Su suscripción necesita renovación";
				break;

			case self::VENCIDA:
				return "Suscripción vencida";
				break;
		}
	}

	/**
	 * Obtener mensajes
	 *
	 * @return bool
	 */
	public function getMessage()
	{
		$nombreEmpresa = $this->empresa->nombre();
		$rucEmpresa = $this->empresa->ruc();
		$fechaVencimiento = $this->empresa->end_plan;
		$diasBorrado = config('app.dias_eliminacion_empresa');

		switch ($this->tipo) {

			case self::POR_VENCER:
				return new HtmlString(sprintf('Te informamos que la suscripción de tu empresa <strong>%s %s </strong>, <span style="color:red"> vence el <strong> %s </strong> </span>, para evitar la suspención del servicio, por favor ingresa al sistema y dirigete a <strong>Menu del usuario > Gestionar Plan </strong> y escoge el plan de tu conveniencia.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));
				break;

			case self::VENCIDA:
				return new HtmlString(sprintf('Te informamos que la suscripción de tu empresa <strong>%s %s </strong>, <span style="color:red"> ha vencido el <strong> %s </strong></span>, para restablecer el servicio, ingrese al sistema y diríjase a <strong>Menu del usuario > Gestionar Plan </strong> y escoge el plan de tu conveniencia. <br> <br> Recuerde que dispone de <strong>%s dias</strong> a partir de la fecha de vencimiento de su plan para renovar antes de que se proceda a la eliminación de su información del sistema.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento, $diasBorrado));
		}
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$saludo =  new HtmlString("<h2>Estado Cliente, <strong>{$notifiable->nombre()} </strong> </h2>");
		$numero = get_setting('numero_soporte', config('app.phones.contacto') );
		$correo = get_setting('sistema_email', config('app.mail.pagos') );
		$informacionContacto = new HtmlString("<br/><br/> Para cualquier consulta puedes comunicarte con nosotros al número telefono (WhatApps) <strong>{$numero}</strong> o por el correo electronico <strong> {$correo} </strong> <br/> <br/> ");

		return (new MailMessage)
			->subject( $this->getAsunto() )
			->line($saludo)
			->line($this->getMessage())
			->line($informacionContacto);
	}
  
	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}
}