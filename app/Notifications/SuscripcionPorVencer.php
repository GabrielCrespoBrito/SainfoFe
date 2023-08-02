<?php

namespace App\Notifications;

use App\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SuscripcionPorVencer extends Notification
{
	use Queueable;
	public $empresa;

	public function __construct(Empresa $empresa)
	{
		$this->empresa = $empresa;
	}

	public function via($notifiable)
	{
		return ['mail'];
	}
  
	public function getMessage()
	{
		$nombreEmpresa = $this->empresa->nombre();
		$rucEmpresa = $this->empresa->ruc();
		$fechaVencimiento = $this->empresa->end_plan;

		return new HtmlString(sprintf('<p style="color:black">Te informamos que la suscripción de tu empresa <strong>%s %s </strong>, <span style="color:red"> vence el <strong> %s </strong> </span></p> <p style="color:black"> Para evitar la suspención del servicio, por favor ingresa al sistema y dirigete a <strong>Menu del usuario > Gestionar Plan </strong> y escoge el plan de tu conveniencia.<p/>', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));
	}

	public function toMail($notifiable)
	{
		$saludo =  new HtmlString("<p style='color:black'>Estado Cliente, <strong>{$notifiable->nombre()} </strong> </p>");

    $titulo = new HtmlString('<h1 style="font-size:30px"> Suscripción Por Vencerse </h1> <hr/>');

		$numero = get_setting('numero_soporte', config('app.phones.contacto') );
		$correo = get_setting('sistema_email', config('app.mail.pagos') );    
		$informacionContacto = new HtmlString("<p style='color:black'> Para cualquier consulta puedes comunicarte con nosotros al número telefono (WhatApps) <strong>{$numero}</strong> o por el correo electronico <strong> {$correo} </strong></p>");

		return (new MailMessage)
			->subject('SAINFO - Su Suscripción Necesita Renovación')
			->line($titulo)
      ->line($saludo)
			->line($this->getMessage())
			->line($informacionContacto);
	}
  
	public function toArray($notifiable)
	{
		return [];
	}
}