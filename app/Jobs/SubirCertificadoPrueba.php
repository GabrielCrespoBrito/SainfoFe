<?php

namespace App\Jobs;

use App\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SubirCertificadoPrueba
{
	use Dispatchable;

	public $empresa;
	public $fileHelper;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Empresa $empresa)
	{
		$this->empresa = $empresa;
		$this->fileHelper = FileHelper($empresa->ruc());
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$pathKey = $this->getPath(config('app.path_cert_demo.key'));
		$pathCer = $this->getPath(config('app.path_cert_demo.cer'));
		$pathPfx = $this->getPath(config('app.path_cert_demo.pfx'));

		$this->saveCert('.key', file_get_contents($pathKey));
		$this->saveCert('.cer', file_get_contents($pathCer));
		$this->saveCert('.pfx', file_get_contents($pathPfx));
	}

	public function getPath( array $config )
	{
		return public_path(call_user_func_array('file_build_path', $config));
	}

	public function saveCert( $ext, $content )
	{
		$this->fileHelper->save_cert( $ext , $content);		
	}

}
