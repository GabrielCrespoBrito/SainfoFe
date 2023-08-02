<?php

namespace App\Notifications;

use App\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use App\Helpers\NotificacionDatabaseHelper;
use Illuminate\Notifications\Messages\MailMessage;

class SuscripcionVencida extends Notification
{
  /* 
  */

  use Queueable;

  
  public $empresa;
  public $info;

  public function __construct(Empresa $empresa)
  {
    $this->empresa = $empresa;
    $this->generateInfo();
  }

  public function via($notifiable)
  {
    return [ 'mail', 'database' ];
  }

  public function generateInfo()
  {
    $nombreEmpresa = $this->empresa->nombre();
    $rucEmpresa = $this->empresa->ruc();
    $fechaVencimiento = $this->empresa->end_plan;

    $descripcion = new HtmlString(sprintf('La suscripción de la empresa <strong>%s %s </strong>, <span style="color:red"> ha vencido el dia de hoy: <strong> %s </strong></span>.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));

    $this->info = (object)  [
      'subject' => 'SAINFO - Suscripción Vencida',
      'titulo' => 'Suscripción Vencida',
      'descripcion' => $descripcion,
      'empresa_id' => $this->empresa->empcodi,
    ];
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

    return new HtmlString(sprintf('La suscripción de la empresa <strong>%s %s </strong>, <span style="color:red"> ha vencido el dia <strong> %s </strong></span>.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));    
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject(  $this->info->subject  )
      ->line( $this->info->titulo )
      ->line($this->info->descripcion );
  }

  public function toDatabase($notifiable)
  {
    return [
      'titulo' => $this->info->titulo,
      'descripcion' => $this->info->descripcion,
      'empresa_id' => $this->info->empresa_id,
      'type' => NotificacionDatabaseHelper::TIPO_DANGER,
      'code' => NotificacionDatabaseHelper::EMPRESA_SUSCRIPCION_VENCIDA,
      'action' => true,
    ];
  }
}
