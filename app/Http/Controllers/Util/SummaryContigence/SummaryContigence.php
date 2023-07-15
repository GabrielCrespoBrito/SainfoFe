<?php 

namespace App\Http\Controllers\Util\SummaryContigence;
use App\SettingSystem;
use Chumper\Zipper\Zipper;

class SummaryContigence 
{
	use NameFilesHandlerTrait;
  /**
   * Nombre del archivo txt
   *
   * @var string
   */
	protected $nameFile;

  /**
   * Ids de los documentos que se van agregar al resumen
   *
   * @var array
   */	
	public $ids;	

  /**
   * Correlativo name
   *
   * @var string
   */ 
  public $correlative;  

  /**
   * Clase para procesar cada documento 
   *
   * @var App\Http\Controllers\Util\SummaryContigence\ProcessDocument
   */ 
  public $processDocument;  
  

  /**
   * Texto final para guardar en el archivo .txt
   *
   * @var string
   */	
	private $txtString = "";


  /**
   * Ubicación del archivo .txt
   *
   * @var string
   */	
	private $pathTxt;

  /**
   * Agregar los ids de los documentos para generar el resumen
   *
   * @param  array  $ids
   * @return void
   */
	public function __construct( Array $ids , $name )
	{
		$this->ids = $ids;
    $this->generateNameFile($name);
    $this->processDocument = new processDocument();
	}



	 /**
   * Ubicación donde guardar el archivo txt
   *
   * @return string
   */
	public function getPathSave()
	{
    return file_build_path( getTempPath() , $this->getNameFileTxt() );
	}

	 /**
   * Devolver el nombre del archivo
   *
   * @return string
   */
	public function geTxtString()
	{
		return trim($this->txtString);
	}

	 /**
   * Devolver el nombre del archivo
   *
   * @return string
   */
	public function addLineToTxt($line , $isFirst)
	{
    $this->txtString .=  $isFirst ? $line : "\n" . $line;
	}

  /**
   * Procesamiento de los documentos pasados por Ids
   *
   * @return void
   */
	public function processIds()
	{
    $isFirst = true;
		foreach ($this->ids as $id) {
			$this->processDocument->load($id, 1);
      $line = $this->processDocument->process();
		  $this->addLineToTxt($line, $isFirst);
      $isFirst = false;
		}
	}

  /**
   * Guardar los documentos ya procesados en un archivo txt
   *
   * @param  array  $ids
   * @return void
   */
	public function saveInTxt()
	{
    \File::put($this->getPathSave(), $this->geTxtString());
	}


  /**
   * Generar documento txt  
   *
   * @return $path
   */
	public function generate()
	{
    $this->processIds();
		$this->saveInTxt();

    return $this->getPathSave();
	}

}

