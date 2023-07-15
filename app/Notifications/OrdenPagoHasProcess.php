<?php

namespace App\Notifications;

use App\Empresa;
use App\Models\Suscripcion\OrdenPago;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class OrdenPagoHasProcess extends Notification
{
	use Queueable;

	public $orden_pago;
	public $empresa;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(OrdenPago $orden_pago, Empresa $empresa)
	{
		$this->orden_pago = $orden_pago;
		$this->empresa = $empresa;
		//
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

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		/* La orden de pago 
        
        La orden de pago #55555 la sido procesada exitosamente, su Plan Empresarial por 12 meses ya se encuentra activo.
        Para ver los detalles sus ordenes y el consumo de su plan, por favor dirijase a Menu usuario > Gestonar Plan / Ordenes   
        Gracias por preferirnos.
        
      */

		$nombreEmpresa = $this->empresa->nombreRuc();
		$saludo = new HtmlString("<h2>Estimado cliente, <strong>{$notifiable->nombre()} </strong> </h2>");
		$numero = get_setting('numero_soporte', '999-999-999');
		$correo = get_setting('sistema_email', 'soporte@sainfo.org.pe');
		$informacionContacto = new HtmlString("<br> <br> Para cualquier pregunta o inconveniente puedes comunicarte con nosotros al número: {$numero} o por el correo electronico {$correo}");

		$orden_id = $this->orden_pago->getIDFormat();
		$planNombre = $this->orden_pago->getNombrePlan();

		$linea1 = new HtmlString("La Orden de Pago <strong>#{$orden_id}</strong> de su empresa <strong>{$nombreEmpresa}</strong>, ha sidoprocesada  <span style='border-bottom: 1px solid green;'>exitosamente</span>.");

		$linea2 = new HtmlString("<br><br> Su <strong>{$planNombre}</strong> ya se encuentra activo.");

		$linea3 = new HtmlString("<br> <br>Para ver su orden de pago y el consumo de su plan, por favor dirijase a <strong>Menu usuario</strong> > <strong>Gestonar Plan</strong> / <strong>Ordenes</strong>.");

		return (new MailMessage)
			->subject("Orden de pago #{$orden_id} ha sido procesada")
			->line($saludo)
			->line($linea1)
			->line($linea2)
			->line($linea3)
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
