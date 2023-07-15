<?php

namespace App\Notifications;

use App\User;
use App\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Helpers\NotificacionDatabaseHelper;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyAdminUserSuscripcionPorVencer extends Notification
{
  use Queueable;

  public $empresa;
  public $info;
  public $userOwner;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Empresa $empresa, $userOwner)
  {
    $this->empresa = $empresa;
    $this->userOwner = $userOwner;
    
    $this->generateInfo();
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return [ 'database' ];
  }

  public function generateInfo()
  {
    $nombreEmpresaCompleto = $this->empresa->nombreRuc();
    $fechaVencimiento = $this->empresa->getEndDateSuscripcion();

    // Saludos
    $saludo = "<h2>Suscripcion por vencerse de <strong>{$nombreEmpresaCompleto} </strong> </h2>";
    // Descripción
    $descripcion = "La suscripción de la empresa <strong> {$nombreEmpresaCompleto} </strong> vence el <strong>{$fechaVencimiento}</strong>." .    
    "<br> El usuario responsable es: <strong> {$this->userOwner->nombre} ({$this->userOwner->usulogi})</strong>, telefono de contacto: <strong>{$this->userOwner->usutele}</strong>";

      $this->info = (object)  [
      'titulo' => 'Suscripcion POR VENCERSE',
      'titulo_email' => $saludo,
      'descripcion' => $descripcion,
      'empresa_id' => $this->empresa->empcodi,
    ];

  }

  
  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    // $nombreEmpresaCompleto = $this->empresa->nombreRuc();
    // $saludo = new HtmlString("<h2>Suscripcion por vencerse de <strong>{$nombreEmpresaCompleto} </strong> </h2>");    
    // $fechaVencimiento = $this->empresa->getEndDateSuscripcion();

    // $linea1 = new HtmlString("La suscripción de la empresa <strong> {$nombreEmpresaCompleto} </strong> vence el <strong>{$fechaVencimiento}</strong>.");

    // $linea2 = new HtmlString("<br> El usuario responsable es: <strong> {$this->userOwner->nombre} ({$this->userOwner->usulogi})</strong>, telefono de contacto: <strong>{$this->userOwner->usutele}</strong>");

    return (new MailMessage)
      ->subject(  $this->info->titulo_email)
      ->line($this->descripcion);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toDatabase($notifiable)
  {
    return [
      'titulo' => $this->info->titulo,
      'descripcion' => $this->info->descripcion,
      'empresa_id' => $this->info->empresa_id,
      'type' => NotificacionDatabaseHelper::TIPO_WARNINIG,
      'code' => NotificacionDatabaseHelper::EMPRESA_SUSCRIPCION_POR_VENCER,
      'action' => true,
    ];
  }
}
