<?php

namespace App\Notifications;

use App\User;
use App\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyAdminUserSuscripcionVencida extends Notification
{
  use Queueable;
  public $empresa;
  public $userOwner;
  public $info;
  
  /**
   * Create a new notification instance.
   *
   * @return void
   */

  public function __construct(Empresa $empresa, User $userOwner)
  {
    /*  */
    $this->empresa = $empresa;
    $this->userOwner = $userOwner;
    $this->generateInfo();
  }

  public function generateInfo()
  {
    // Descripción
    $descripcion = "";

    $this->info = (object)  [
      'subject' => 'SAINFO - Suscripción Vencida',
      'titulo' => 'Suscripción Vencida',
      'descripcion' => $descripcion,
      'empresa_id' => $this->empresa->empcodi,
    ];
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $nombreEmpresaCompleto = $this->empresa->nombreRuc();

    $saludo = new HtmlString("<h2>Suscripcion vencida de <strong>{$nombreEmpresaCompleto} </strong> </h2>");
    $fechaVencimiento = $this->empresa->getEndDateSuscripcion();

    $linea1 = new HtmlString("La suscripción de la empresa <strong> {$nombreEmpresaCompleto} </strong> ha vencido el dia <strong>{$fechaVencimiento}</strong>.");

    $linea2 = new HtmlString("<br>El usuario responsable es: <strong> {$this->userOwner->nombre} ({$this->userOwner->usulogi})</strong>, telefono de contacto: <strong>{$this->userOwner->usutele}</strong>");

    return (new MailMessage)
      ->subject("Suscripcion vencida")
      ->line($saludo)
      ->line($linea1)
      ->line($linea2);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toDatabase($notifiable)
  {
    return [];
  }
}
