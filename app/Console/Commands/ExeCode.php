<?php

namespace App\Console\Commands;

use App\Cotizacion;
use App\Empresa;
use App\GuiaSalida;
use App\Http\Controllers\Util\SummaryContigence\SummaryContigence;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Models\UserLocal\UserLocal;
use App\Notifications\EmpresaDocPendientePorEnviar;
use App\Notifications\EmpresaRegister;
use App\SerieDocumento;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ExeCode extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'util:exeCode {code=001} {param=false}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

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
    $code = $this->argument('code');
    $param = $this->argument('param');
    switch ($code) {
      case 'api-guia':
        $this->apiGuia();
        break;
      case 'user-local':
        $this->addUserLocal();
        break;
      case 'regenerate-pdf-guia':
        $this->regeneratePdfGuia();
        break;
      default:
        break;
    }
  }

  public function regeneratePdfGuia()
  {
    $params = explode("|", $this->argument('param'));
    
    $empcodi = $params[0];
    $serie = $params[1];
    $numcodi = $params[2];
    $direccionLlegada = $params[3];

    $empresa = Empresa::find($empcodi);
    (new ActiveEmpresaTenant($empresa))->handle();

    $guia = GuiaSalida::where('GuiSeri', $serie)->where('GuiNumee', $numcodi)->first();

    $guia->update([
      'guidill' => $direccionLlegada ?? $guia->guidill
    ]);

    $guia->deletePdf(true, $empresa->ruc());
  }

  public function apiGuia()
  {
    $empresa = Empresa::find($this->argument('param'));
    $res = $empresa->getOrGenerateGuiaTokenApi();
    dd($res);
    exit();
  }

  public function addUserLocal()
  {
      $empresas = Empresa::all(); 
      
      foreach( $empresas as $empresa ){
          try {
          empresa_bd_tenant($empresa->empcodi);
          $locales = $empresa->locales; 
          foreach( $locales as $local ){
              UserLocal::create_( '01', $local->LocCodi, $empresa->empcodi );
            }
          }
            catch( Exception $th ){
            logger('@ERROR ExeCode AddUserLocal ' . $th->getMessage());
      }
  }
}


  public function addSeriesToUsers()
  {
    $empresas_group = Empresa::all()->chunk(50);
    $parametros = config('enums.empresa_parametros');
    foreach ($empresas_group as $empresas) {
      foreach ($empresas as $empresa) {
        empresa_bd_tenant($empresa->id());
        try {
          $locales = $empresa->locales;
          foreach ($locales as $local ) {
            $usuarios_locales = $local->usuarios_locales->unique('usucodi');
            foreach ($usuarios_locales as $usuario_local) {
              $sercodi = 'OC0' . substr($usuario_local->loccodi, -1);
              $data = [
                'empcodi' => $usuario_local->empcodi,
                'usucodi' => $usuario_local->usucodi,
                'tidcodi' => Cotizacion::ORDEN_COMPRA,
                'loccodi' => $usuario_local->loccodi,
                'numcodi' => 0,
                'sercodi' => $sercodi,
                'defecto' => 0,
                'contingencia' => 0,
                'a4_plantilla_id' => 8,
                'a5_plantilla_id' => 8,
                'ticket_plantilla_id' => 8,
                'impresion_directa' => 0,
                'cantidad_copias' => 0,
                'nombre_impresora' => null
              ];
              SerieDocumento::create($data);
            }
          }
        } catch (\Throwable $th) {
          Log::info(
            sprintf('@ERROR ExeCodeAddSeriestoUsers EMPRESA (%) Error (%s)', $empresa->id, $th->getMessage())
          );
        }
      }
    }
  }
}
