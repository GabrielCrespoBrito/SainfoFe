<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class CreateDefaultDatabase extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:create_default';
	protected $process;
	protected $databaseName;
	public $comando;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Crea la base de datos por defecto de una empresa';

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
		$this
			->createDatabase()
			->runSQL();

		$this->process->mustRun();

		$this->info("Creada Database {$this->getDatabaseName()} comando: {$this->comando}");
	}



	public function createDatabase()
	{
		$databaseName = strtolower(str_random(10));

		$this->setDatabaseName($databaseName);

		DB::statement("CREATE SCHEMA `{$databaseName}`");

		return $this;
	}

	public function runSQL()
	{
		$mysqldump_folder = isWindow() ? get_setting('mysqldump_path') : "mysqldump";
		$pathSql = file_build_path(public_path(), 'static', 'db_plantilla', 'plantilla.sql');
		$content = file_get_contents($pathSql);

		$this->comando = sprintf(
			"%s -u%s -p%s -e %s < %s",
			// "%s -u%s -p%s %s ",
			$mysqldump_folder,
			config('database.connections.mysql.username'),
			config('database.connections.mysql.password'),
			$this->getDatabaseName(),
			$pathSql
		);

		$this->process = new Process($this->comando, null, null, null, 300);
		$this->process->mustRun();
	}


	/**
	 * Get the value of databaseName
	 */
	public function getDatabaseName()
	{
		return $this->databaseName;
	}

	/**
	 * Set the value of databaseName
	 *
	 * @return  self
	 */
	public function setDatabaseName($databaseName)
	{
		$this->databaseName = $databaseName;

		return $this;
	}
}