<?php

namespace App\Jobs\Resumen;

use App\XmlHelper;

class ContentCDR
{
	protected $content;
  protected $nameFile;
  protected $nameFileXML;
  protected $tempPath;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct( $content, $nameFile , $nameFileXML )
	{
		$this->content = $content;
    $this->nameFile = $nameFile;
    $this->nameFileXML = $nameFileXML;
	}

  public function getTempPath()
  {
    return $this->tempPath;
  }

  public function saveTemp()
  {
    $this->tempPath = FileHelper()->saveTemp($this->content, $this->nameFile );    

    return $this;
  }

  public function saveCDR()
  {
    FileHelper()->save_cdr($this->nameFile ,$this->content);
    return $this;
  }

  public function extraerContent( array $infoExtract)
  {
    return XmlHelper::extract_value( $infoExtract , $this->getTempPath() , $this->nameFileXML , true );
  }
}
