<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use App\Periodo;
use App\EmpresaOpcion;
use Illuminate\Http\Request;

class CreateEmpresaForUser 
{
	public $user;
  public $empresa;
	public $request;

	/** 
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($user, Request $request)
	{
		$this->user = $user;
		$this->request = $request;
		$this->setEmpresa(new Empresa());
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->createEmpresa();
		$this->createOpcionEmpresa();
	}

	public function getData()
	{
		return (object) [
			'razon_social' => $this->request->input('razon_social'),
			'ruc' => $this->request->input('ruc'),
			'direccion' => $this->request->input('direccion'),
			'email' => $this->request->input('email'),
			'telefonos' => $this->user->usutele,
			'nombre_comercial' => $this->request->input('nombre_comercial')
		];
	}

	// CREAR EMPRESA;
	public function createEmpresa()
	{
		$dataEmpresa = $this->getData();

		$data = [];
		$data['nombre_empresa'] = $dataEmpresa->razon_social;
		$data['ruc'] = $dataEmpresa->ruc;
		$data['direccion'] = $dataEmpresa->direccion;
		$data['email'] = $dataEmpresa->email;
		$data['telefonos'] = $this->user->usutele;
		$data['nombre_comercial'] = $dataEmpresa->nombre_comercial;
 		$data['rubro'] = '';
		$data['departamento'] = null;
		$data['provincia'] = null;
		$data['distrito'] = null;
		$data['ubigeo'] = null;
		$data['clave_firma'] = config('app.clave_firma');
		$data['usuario_sol'] = config('app.usuario_sol');
		$data['clave_sol'] = config('app.clave_sol');
		$data['fe_servicio'] = '1';
		$data['fe_ambiente'] = Empresa::DESARROLLO;
		$data['formato_hoja'] = '';
		$data['tipo_envio_servicio'] = '';
		$data['version_xml'] = "2.1";
		$data['fe_formato'] = "0";
		$data['formato_hoja'] = "0";
		$data['OPC'] = "0";
    $data['EmpPReten'] = $this->user->getPlanRegister();
    $data['fe_envfact']  = 1;
    $data['fe_envbole']  = 0;
    $data['fe_envncre']  = 1;
		$data['fe_envndebi'] = 1;
    $data['tipo_plan'] = Empresa::PLAN_DEMO;
		$data['FE_REPO'] = "11";
    $img = \Image::make(  public_path( file_build_path('static','demo', 'logo.png') ) );
    $img->encode('jpeg');
    $data['logo_principal'] = $img;
    // ususerf
		$data['fe_consulta'] = config('app.url_busqueda_documentos');
		$data['active'] = 1;
		$this->empresa = Empresa::saveData( $data, null , true );
	}


	public function createOpcionEmpresa()
	{
		$empresa = $this->empresa;
		# Empresa 
		EmpresaOpcion::createDefault($empresa->empcodi);
		# Local
		Periodo::createDefault($empresa->empcodi);
	}

	/**
	 * Get the value of empresa
	 */
	public function getEmpresa()
	{
		return $this->empresa;
	}

	/**
	 * Set the value of empresa
	 *
	 * @return  self
	 */
	public function setEmpresa($empresa)
	{
		$this->empresa = $empresa;

		return $this;
	}
}