<?php

namespace App\Console\Commands;

use App\TipoCambioPrincipal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Util\ConsultTipoCambio\ConsultTipoCambioInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByDateInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByLatestInterface;

class ConsultTipoCambioDia extends Command
{
  public $consulter;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:actualizar_tipo_cambio';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Consultar y guardar el tipo de cambio del dia proveniente de la sunat';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(ConsultTipoCambioByLatestInterface $consulter)
  {
    parent::__construct();

    $this->consulter = $consulter;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $tc = TipoCambioPrincipal::getToday();

    // if (optional($tc)->isSameDay()) {
      // return;
    // }

    if ( ! $tc) {
      $tc = new TipoCambioPrincipal(); 
    }

    $this->consultSave($tc);
  }

  public function consultSave( TipoCambioPrincipal $tc )
  {
    $consult_tc = $this->consulter->consult();

    if ( ! $consult_tc['success']) {
      return;
    }

    if ( ! ( $consult_tc['success'] && $data_consult = $consult_tc['data'])) {
      return;
    }

    if ( ! $data_consult->success ) {
      return;
    }

    // if(  $tc->TipFechReal == $data_consult->fecha ){
      // return;
    // }

    // Save Tipo Cambio
    $tc->TipFech = $data_consult->fecha;
    $tc->TipComp = $data_consult->precio_compra;
    $tc->TipVent = $data_consult->precio_venta;
    $tc->TipFechReal = $data_consult->fecha;

    if(  $tc->isDirty() ){
      $tc->save();
      Cache::forget('ultimo_tc');
    }
  }

}