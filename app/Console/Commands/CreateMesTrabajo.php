<?php

namespace App\Console\Commands;

use App\M;
use App\Mes;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class CreateMesTrabajo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:crear_mes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consultar si el mes en el que estamos no esta creado, para en dicho caso crear su registro';

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
      $mes = Mes::firstOrCreate(['mescodi' => Carbon::now()->format('Ym')], []);
      
      if($mes->wasRecentlyCreated){
        cache()->forget('MESES.ALL');
      }
      
    }
}
