<?php

namespace App\Console;

use App\Console\Commands\TinkerCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    Commands\VerificarDataBase::class,
    Commands\DeleteTempFiles::class,
    Commands\MandarDocumentos::class,
    Commands\MandarResumens::class,
    Commands\MandarGuias::class,
    Commands\ConsultTipoCambioDia::class,
    Commands\InstalacionSistema::class,
    Commands\RespaldoDatabase::class,
    Commands\CreateMesTrabajo::class,
    Commands\InformeDocumentosDiarios::class,
    Commands\GivePermissionToAllUsers::class,
    Commands\TicketVenta::class,
    Commands\LogMessage::class,
    Commands\CrearContador::class,
    Commands\ExeCode::class,
    Commands\SendGuiaSunat::class,
    Commands\CreateDefaultDatabase::class,
    Commands\VerifySuscripciones::class,
    Commands\ResetSuscripcionCaracteristicas::class,
    Commands\ActualizarAT::class,
    Commands\CreateUpdateAnioEmpresas::class,
    Commands\UpdateDocumentoRefGuias::class,
    Commands\EnviarDocPendienteCommand::class,
    TinkerCommand::class,
    Commands\SetLocalSerieCodigo ::class,    
    Commands\SincronizarMediosPagos::class,
    Commands\CrearMedioPagoCreditoPorDefecto::class,
    Commands\ExeTarea::class,
    Commands\AddEmpresaAditionalParametro::class,
    Commands\CreatePDFPlantilla::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    set_timezone();

    # Mensaje escrito en el log para saber que se estan ejecutando las tareas
    $schedule->command('util:log_mensaje')->hourly();

    # Eliminar archivos temporales
    $schedule->command('eliminar:temp')->dailyAt('02:00');

    # Crear mes nuevo 
    $schedule->command('system_task:crear_mes')->monthlyOn();

    # Actualizar el tipo de cambio diariamente
    $schedule->command('system_task:actualizar_tipo_cambio')
      ->hourly()
      ->between('00:00', '12:00');

    $schedule->command('system_task:enviar_doc_pendientes')->dailyAt('23:00');
    $schedule->command('system_task:enviar_doc_pendientes')->dailyAt('13:00');

    # Reiniciar las estadisticas de la suscripción
    $schedule->command('suscripciones:mes_reinicio')->dailyAt("00:00");

    # Respaldo de las bases de datos
    $schedule->command('db:respaldo')->dailyAt("00:05");

    # Mandar emails de notificaciones de vencimiento de la suscripón de las empresas
    $schedule->command('suscripciones:notificaciones')->dailyAt("00:15");

    # Ejecutar la tarea de crear el año de trabajo el inicio del año
    $schedule->command('system_task:actualizar_anio_trabajo')->yearly();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');
    require base_path('routes/console.php');
  }
}
