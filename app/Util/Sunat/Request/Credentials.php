<?php

namespace App\Util\Sunat\Request;

class Credentials
{	
	/*
	* Poner las credenciales manualmente
	*
	*/
	protected $credentialsForce = false;

	/*
	* Usuario y contraseña
	*
	*/	
	protected $username;
	protected $password;

	/*
	* Credenciales
	*
	*/
	private $credentials = [];


	public function __construct( array $credentials = [] )
	{
		$this->credentials = $credentials;
		$this->credentialsForce = (bool) count($credentials);
		$this->makeCredentials();
	}

	/**
	* Validate credentials from construct if exists 
	*	
	* @return void
	*/
	public function validateInputCredentials()
	{

		if( $this->credentialsForce ){
			if( !isset($this->credentials['username']) && !isset($this->credentials['password']) ){
				throw new \Exception("For set input credentials has array with key-values: username y password", 1);
			}
		}		
		else {
		// @TODO: Should validate credentails from bussiness
		}
	}

	private function setUsername($username)
	{	
		$this->username = $username;
	}

	/**
	 * Obtener nombre de usuario
	 * 
	 * @return string
	 */
	public function getUsername()
	{	
		return $this->username;;
	}

	/**
	 * Obtener contraseña del usuario
	 * 
	 * @return string
	 */

	private function setPassword($password)
	{	
		$this->password = $password;
	}

	public function getPassword()
	{	
		return $this->password;
	}

	public function makeCredentials(){
		$this->validateInputCredentials();
		$this->resolverCredentials();
	}

	public function resolverCredentials()
	{
		$this->credentialsForce ? $this->setCredentialsFromArray() : $this->setCredentialsFromBussiness();
	}

	public function setCredentialsFromBussiness()
	{
		$empresa = get_empresa();			
		$username = $empresa->EmpLin1 . $empresa->FE_USUNAT;
		$password = $empresa->FE_UCLAVE;

		$this->setUsername($username);
		$this->setPassword($password);
	}


	public function setCredentialsFromArray()
	{
		$this->setUsername( $this->credentials['username'] );
		$this->setPassword( $this->credentials['password'] );
	}
}
