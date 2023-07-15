<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GuiaSalida\UpdateGuiaDetalleCampoCantAbs;

class ExeTarea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:ejecutar_codigo {code} {param1=-1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecutar Codigos Especificos';

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
      $param1 = $this->argument('param1');

      switch ($code) {
        case 'actualizar_guia_detalle_cant_abs':
          $empresa_id = $param1 == "-1" ? null : $param1;
          (new UpdateGuiaDetalleCampoCantAbs())->handle($empresa_id);
          break;
        
        default:
          return 
            $this->error(sprintf( "El Codigo (%s) Para Ejecutar no existe", $code));
          break;
      }
    }
}
