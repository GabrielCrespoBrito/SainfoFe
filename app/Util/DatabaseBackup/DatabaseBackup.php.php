<?php

namespace App\Util\DatabaseBackup;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup
{
	public function __construct($databaseName, $pathStorage)
	{
		$this->databaseName = $databaseName;
		$this->pathStorage = $pathStorage;
	}

	public function getCommand()
	{
		$commando = sprintf(
			"mysqldump -u%s -p%s %s > %s",
			config('database.connections.mysql.username'),
			config('database.connections.mysql.password'),
			$this->databaseName,
			$this->pathStorage
		);
	}


	public function make()
	{
		try {
			$process = new Process( $this->getCommand() , null, null, null, 300);
			$process->mustRun();			
		} catch (ProcessFailedException $th) {
			return false;
		}
		return true;
	}





}
