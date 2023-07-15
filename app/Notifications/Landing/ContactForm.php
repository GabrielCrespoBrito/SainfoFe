<?php

namespace App\Notifications\Landing;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ContactForm extends Notification
{
	use Queueable;

	public $data;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
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
		$linea1 = new HtmlString("<strong>Correo electronico, enviado desde el formulario de contacto</strong>");
		$linea2 = new HtmlString("Informacion: <br/> <br/>");
		$linea3 = new HtmlString("Ruc: <strong>{$this->data['ruc']} </strong> <br/>");
		$linea4 = new HtmlString("Razon Social: <strong>{$this->data['razon_social']} </strong> <br/>");
		$linea5 = new HtmlString("Telefono: <strong>{$this->data['telefono']} </strong> <br/>");
		$linea6 = new HtmlString("Email: {$this->data['email']} <br/>");
		$linea7 = new HtmlString("Mensaje: <strong>{$this->data['mensaje']}</strong> <br/>");

		return (new MailMessage)
			->subject('Correo formulario de contacto.')
			->line($linea1)
			->line($linea2)
			->line($linea3)
			->line($linea4)
			->line($linea5)
			->line($linea6)
			->line($linea7);
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
