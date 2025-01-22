<?php 

namespace App\Http\Controllers\Util\RespaldoBaseDato;

/**
*
* Clase para comprmir archivos o directorios en .rar 
* 
**/
class CompressRar
{
	// Ubicación del exe o comando para ejecutar la compresión en .rar
	private $rarPathOrCommandExe;
	// Ubicación del archivo comprimido resultante
	private $pathFileCompress;
	// Ubicación del archivo comprimido que se va a comprimir
	private $pathFileToCompress;
	// Modificadores para ejecutar el comando rar
	private $modifiers;


	function __construct( $pathFileToCompress, $pathFileCompress )
	{
		// Archivo que comprimir
		$this->pathFileToCompress = $pathFileToCompress;

		// Archivo comprimido
		$this->pathFileCompress = $pathFileCompress;

		// Path donde se ubica el .exe (o en el caso de linux el nombre del comando) para ejecutar la compresión.
		$this->rarPathOrCommandExe = get_setting('path_rar' , false);

		// Verificar que exista en las configuraciones la dirección y/o comando
		if( $this->rarPathOrCommandExe === false ){ 
			throw new \Exception("No se encuentra en las configuraciónes (path_rar) la direccion o comando para la compresión", 1);	
		}		
		
		// Modifiers del archivo
		$this->modifiers = isWindow() ? "a -ep1 -idq -ibck" : "";
	}

	public function getCommand()
	{
		return sprintf('%s %s %s %s' ,
			$this->rarPathOrCommandExe , 
			$this->modifiers,
			$this->pathFileCompress , 
			$this->pathFileToCompress 
	);

	}

	public function make()
	{
    $command = $this->getCommand();
    exec($command);
	}
}