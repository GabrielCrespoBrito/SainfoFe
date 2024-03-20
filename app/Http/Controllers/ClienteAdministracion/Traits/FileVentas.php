<?php

namespace App\Http\Controllers\ClienteAdministracion\Traits;

use App\Venta;
use App\Empresa;

trait FileVentas
{
	public $empcodi;
	public $fileHelper;

	public function getFiles( $id ){

		$venta = Venta::find($id);
		$docs = [ 'xml' , 'pdf' , 'cdr' ];
		$pathFiles = [];

		$nameFiles = [
			'pdf' => $venta->nameFile('.pdf') , 
			'xml' => $venta->nameFile('.xml') , 
			'cdr' => $venta->nameCdr('.zip')
		];
		foreach ( $docs as $doc ) {

			if( $venta->hasDoc($doc) ){

				$nameFile = $nameFiles[$doc];
				switch ($doc) {
					case 'pdf':
						$contentFile = $this->fileHelper->getPdf($nameFile);
						break;
					case 'xml':
						$contentFile = $this->fileHelper->getEnvio($nameFile);
						break;
					case 'cdr':
						$contentFile = $this->fileHelper->getCdr($nameFile);
						break;													
				}
				array_push($pathFiles, $this->fileHelper->saveTemp($contentFile, $nameFile));
			}
		}

		return $pathFiles;
	}

	public function saveFiles( $ids = [] , $empcodi )
	{
		$this->empcodi = $empcodi;
		$this->ruc_empresa = Empresa::find( $this->empcodi )->EmpLin1;
		$this->fileHelper = FileHelper($this->ruc_empresa);

		$pathFiles = [];

		if( count($ids) ){

			foreach( $ids as $id ){
				$pathFilesVenta = $this->getFiles( $id );
				$pathFiles = array_merge( $pathFiles , $pathFilesVenta );
			}

			if( count( $pathFiles ) ){
				$name_comprimido = get_empresa()->EmpLin1 . '_' . date('Ymdhms') . '.zip';
				$path_comprimido = $this->fileHelper->getTempPath($name_comprimido);
				$zipper = \Zipper::make($path_comprimido);

        foreach ( $pathFiles as $path ) {
					$zipper->add( $path );
				}

				$zipper->close();
				
        return [
          'name' => $name_comprimido,
          'path' => $path_comprimido
        ];
			}
		}

		return false;
	}


}