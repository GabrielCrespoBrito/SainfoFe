<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SuscripcionPorTerminar extends Notification
{
    use Queueable;

    public $empresa;
    // public $empresa;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($empresa)
    {
        $this->empresa = $empresa;
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
        $nombreEmpresa = $this->empresa->nombre();
        $rucEmpresa = $this->empresa->ruc();        
        $fechaVencimiento = $this->empresa->end_plan;
        $saludo =  new HtmlString("<h2>Hola, <strong>{$notifiable->nombre()}</strong> </h2>");
        $html = new HtmlString(sprintf('Te informamos que suscripción de su empresa <strong>%s %s </strong>, vence el <strong> %s </strong>, para seguir disfrutando del servicio por favor, ingresa al sistema y en el <strong>Menu del usuario</strong>, darle click al enlace de  <strong>Planes</strong>, y escoge el plan de tu conveniencia.
        ', $nombreEmpresa, $rucEmpresa, $fechaVencimiento   ));

        return (new MailMessage)
          ->line($saludo)
          ->line($html);
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
