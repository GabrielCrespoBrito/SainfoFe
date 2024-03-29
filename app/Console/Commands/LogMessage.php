<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogMessage extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'util:log_mensaje';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Escribir mensaje en el archivo de logs';

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
    logger('Log message from command util:log_mensaje ' . date('Y-m-d H:i:s'));
  }
}
