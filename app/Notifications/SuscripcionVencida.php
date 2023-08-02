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
  public $userOwner;
  public $info;

  public function __construct(Empresa $empresa, $userOwner)
  {
    $this->empresa = $empresa;
    $this->userOwner = $userOwner;
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

    $descripcion_titulo = new HtmlString('<h1 style="font-size:30px"> Suscripción Vencida </h1> <hr/>');

    $name = $this->userOwner->getNombre();

    $lineaSaludo = new HtmlString(sprintf('<p style="color:black">Hola %s</p>', $name ));

    $descripcion = new HtmlString(sprintf('<p style="color:black">La suscripción de la empresa <strong>%s %s </strong>, ha vencido en fecha: (<strong> %s </strong>) </p>', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));
    
    $lineaPasos = new HtmlString(sprintf(' <hr/> <p style="color:black">Para renovar su suscripción, ingrese al sistema y dirigete a <strong>Menu del usuario > Gestionar Plan </strong> y escoge el plan de tu conveniencia.</p> <hr/>', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));

    $lineaAdvertencia = new HtmlString(sprintf('<p style="color:black">Renueve su suscripción para continuar disfrutando del servicio.</p>', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));

    $numero = get_setting('numero_soporte', config('app.phones.contacto'));
    $correo = get_setting('sistema_email', config('app.mail.pagos'));
    $lineaContacto = new HtmlString("<p style='color:black'>Para cualquier consulta puedes comunicarte con nosotros al número telefono (WhatApps) <strong>{$numero}</strong> o por el correo electronico <strong> {$correo} </strong> </p>");



    $this->info = (object)  [
      'subject' => 'SAINFO - Suscripción Vencida',
      'titulo' => 'Suscripción Vencida',
      'descripcion_titulo' => $descripcion_titulo,
      'descripcion' => $descripcion,
      'lineaSaludo' => $lineaSaludo,
      'lineaPasos' => $lineaPasos,
      'lineaAdvertencia' => $lineaAdvertencia,
      'lineaContacto' => $lineaContacto,
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

    return new HtmlString(sprintf('<p>La suscripción de la empresa <strong>%s %s </strong>, ha vencido el dia <strong> %s </strong>.', $nombreEmpresa, $rucEmpresa, $fechaVencimiento));    
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject(  $this->info->subject  )
      ->line($this->info->descripcion_titulo)
      ->line($this->info->lineaSaludo)
      ->line($this->info->descripcion)
      ->line($this->info->lineaPasos)
      ->line($this->info->lineaContacto);
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
