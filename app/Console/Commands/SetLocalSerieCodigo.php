<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetLocalSerieCodigo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:local_numero_serie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Guardar en la tabla local, su respectivo numero de serie utilziado';

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
      $empresas = Empresa::all();
      foreach ($empresas as $empresa) {
        try {
          empresa_bd_tenant($empresa->empcodi);
          foreach( $empresa->locales as $local ){
            $serie = $local->usuarios_documentos
            ->where('empcodi', $local->EmpCodi)
            ->where('tidcodi', '01' )->first();
            
            $local->update(['SerLetra' => substr($serie->sercodi, -3 )]);                     
          }
        } catch (\Throwable $th) {
        }
      }


    }
}




