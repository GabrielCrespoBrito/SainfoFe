<?php

namespace App\Console\Commands;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Console\Command;

class InstalacionSistema extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'instalacion';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ejecutar todas las tarea para la instalación';

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
	  \Artisan::call('verificar:database');        
	  \Artisan::call('db:seed', [ '--class' => "SettingsTableSeeder"]);        
	  \Artisan::call('db:seed', [ '--class' => "PermissionSeeder"]);
	}
}
