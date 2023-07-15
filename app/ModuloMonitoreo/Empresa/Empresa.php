<?php

namespace App\ModuloMonitoreo\Empresa;

use App\ModuloMonitoreo\DocSerie\DocSerie;
use App\ModuloMonitoreo\Document\Document;
use App\Util\ModelUtil\ModelUtil;
use App\Util\Sunat\Request\credentials\CredentialManual;
use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Empresa extends Model
{
	use ModelUtil;

	public $descripcionKey = "descripcion";
	protected $table = "monitor_empresas";
	public $fillable = ['razon_social', 'ruc' , 'telefono' , 'email', 'descripcion', 'code' , 'usuario_sol' , 'clave_sol', 'proveedor', 'cant_docs'];
	public $timestamps = false;
	protected $appends = ['nameDocument'];
	public $codeCero = 2;


	public function getNameDocumentAttribute()
	{
		return $this->ruc . ' | ' . $this->razon_social;
	}

	public function usuarioSolReal()
	{
		return $this->ruc . $this->usuario_sol;
	}

	public function passwordSol()
	{
		return $this->clave_sol;
	}	


	public function getCommunicator()
	{
		$usuarioSolReal = $this->usuarioSolReal();
		$passwordSol = $this->passwordSol();
		$resolver =  new ConsultStatusResolver( 
			$this->proveedor, 
			new CredentialManual($usuarioSolReal, $passwordSol), 
			true
		);

		return $resolver->getCommunicator();
	}

	public function isNube()
	{
		return $this->proveedor == ResolverWsld::NUBEFACT;
	}

	public function isSunat()
	{
		return $this->proveedor == ResolverWsld::SUNAT;
	}

	public function series()
	{
		return $this->hasMany( DocSerie::class, 'empresa_id');
	}

	public function documents()
	{
		return $this->hasMany( Document::class, 'empresa_id' );
	}

	public function setUsuarioSolAttribute($val)
	{
		$this->attributes['usuario_sol'] = trim($val);
	}

	public function setClaveSolAttribute($val)
	{
		$this->attributes['clave_sol'] = trim($val);
	}

	public function setRazonSocialAttribute($val)
	{
		$this->attributes['razon_social'] = trim($val);
	}	

	public function getRealUserSol()
	{
		return $this->ruc . $this->usuario_sol;
	}

	public function getFolderSave( $folder = null )
	{
		$carpetaGuardado = get_setting('carpeta_guardado');
		$carpetaGuardado = substr($carpetaGuardado, 0, strlen($carpetaGuardado) - 1);		

		$path = file_build_path($carpetaGuardado, 'monitoreo', $this->ruc );

		return $folder ? file_build_path($path, $folder) : $path;
	}

	public function getPathCer( $ext )
	{
		$fileNameCert = sprintf('%s.%s' , $this->ruc , $ext );		
		return file_build_path($this->getFolderSave('cert') , $fileNameCert );
	}

	public function saveCert( $ext, UploadedFile $uploadFile )
	{
		\File::put( $this->getPathCer($ext), file_get_contents( $uploadFile->path() ));
	}

	public function getCodeAttribute($val)
	{
		return $this->exists ? $val : $this->getLastIncrement('code');
	}
	public function getDescripcionAttribute($val)
	{
		return $val;
	}

	public function updateCantidadDocs()
	{
		$cant = 0;

		$cant = \DB::table('monitor_empresa_documentos')
		->join('monitor_empresa_series', 'monitor_empresa_series.id' , '=' , 'monitor_empresa_documentos.serie_id')
		->join('monitor_empresas', 'monitor_empresas.id', '=', 'monitor_empresa_series.empresa_id')
		->where('monitor_empresas.id' , $this->id )
		->count();

		$this->update([
			'cant_docs' => $cant,
		]);
	}
}