<?php

namespace App\Console\Commands;

use App\Venta;
use Illuminate\Console\Command;

class GenerateTxtSunat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:sunat_txt --{mes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar txt para comprobar en la sunat validez de facturas';

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
    }
}
