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

		return new HtmlString(sprintf('Te informamos que la suscripción de tu empresa <strong>%s %s </strong>, <span style="color:red"> vence el <strong> %s </strong> </span>, para evitar la suspención del servicio, por favor ingresa al sistema y dirigete a <strong>Menu del usuario > Gestionar Plan </strong> y escoge el plan de tu conveniencia.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));
	}

	public function toMail($notifiable)
	{
		$saludo =  new HtmlString("<h2>Estado Cliente, <strong>{$notifiable->nombre()} </strong> </h2>");
		$numero = get_setting('numero_soporte', config('app.phones.contacto') );
		$correo = get_setting('sistema_email', config('app.mail.pagos') );    
		$informacionContacto = new HtmlString("<br/><br/> Para cualquier consulta puedes comunicarte con nosotros al número telefono (WhatApps) <strong>{$numero}</strong> o por el correo electronico <strong> {$correo} </strong> <br/> <br/> ");

		return (new MailMessage)
			->subject('SAINFO - Su Suscripción Necesita Renovación')
			->line($saludo)
			->line($this->getMessage())
			->line($informacionContacto);
	}
  
	public function toArray($notifiable)
	{
		return [];
	}
}