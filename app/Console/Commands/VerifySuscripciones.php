<?php

namespace App\Console\Commands;

use App\User;
use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\VencimientoSuscripcion;
use App\Notifications\NotifyAdminUserSuscripcionVencida;
use App\Notifications\NotifyAdminUserSuscripcionPorVencer;
use App\Notifications\SuscripcionPorVencer;
use App\Notifications\SuscripcionVencida;
use App\Notifications\SuscripcionVencidaHoy;

class VerifySuscripciones extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'suscripciones:notificaciones';

  /**
   * The console command description.
   * 
   * @var string
   */
  protected $description = 'Ver que suscripciones estan por vencerse o vencidas para enviar informacion al usuario';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    // Iterar para ver que suscripciones estan por vencerse

    $empresas = Empresa::all();
    $user_soporte = User::getUserSoporte();

    foreach ($empresas as $empresa) {
      
      try {
        $datesSuscripcion = $empresa->getSuscripcionDates();
        $userOwner = $empresa->userOwner();
        

        if( !$userOwner ){
          // continue;
        }

        # Si hoy es la fecha de recordatorio ANTES del vencimiento
        if ($datesSuscripcion->isFechaRecodatorio) {
          $userOwner->notify(new SuscripcionPorVencer($empresa));
          $user_soporte->notify(new NotifyAdminUserSuscripcionPorVencer($empresa, $userOwner));
        }
        
        //Si hoy es la fecha de vencimiento, osea se vencio
        else if ($datesSuscripcion->isFechaVencimiento) {
          $userOwner->notify(new SuscripcionVencidaHoy($empresa));
          $user_soporte->notify(new SuscripcionVencida($empresa));
        }

        // Si es la fecha despues de la fecha de vencimiento 
        else if ($datesSuscripcion->isFechaRecordatorioVencimiento) {
          // $userOwner->notify(new SuscripcionVencida($empresa));
        }
        
      } catch (\Throwable $th) {
        $usulogi = $userOwner->usulogi;
        $email = $userOwner->email;
        $nombre_empresa = $empresa->nombreRuc();
      }

    }



  }
}
