<?php

namespace App\Console\Commands;

use App\User;
use App\Empresa;
use App\Cotizacion;
use GuzzleHttp\Client;
use App\SerieDocumento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\EmpresaRegister;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmpresaDocPendientePorEnviar;
use App\Http\Controllers\Util\SummaryContigence\SummaryContigence;

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
      default:
        break;
    }
  }

  public function apiGuia()
  {
    $empresa = Empresa::find($this->argument('param'));
    $res = $empresa->getOrGenerateGuiaTokenApi();
    dd($res);
    exit();


    // $client_id = "70342b18-1a58-4acc-8f34-cb0b4648dee4";
    // $clave = "YJGpzCO8qyjY4gsC7mCfyg==";
    // https: //api-seguridad.sunat.gob.pe/v1/clientessol/%3cclient_id%3e/oauth2/token/
    // https://api-seguridad.sunat.gob.pe/v1/clientessol/70342b18-1a58-4acc-8f34-cb0b4648dee4/oauth2/token/

    // Validate ReCaptcha
    // $client = new Client([
    //   'base_uri' => 'https://google.com/recaptcha/api/'
    // ]);
    // $response = $client->post('siteverify', [
    //   'query' => [
    //     'secret' => env('CAPTCHA_SECRET'),
    //     'response' => $value
    //   ]
    // ]);
    // return json_decode($response->getBody())->success;

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
