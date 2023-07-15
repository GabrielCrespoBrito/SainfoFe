<?php 

namespace App\Http\Controllers\Util\SummaryContigence;

use App\Models\Contingencia\Contingencia;


trait NameFilesHandlerTrait 
{
  /**
   * Generar el nombre del archivo
   *
   * @param  array  $ids
   * @return void
   */
  public function generateNameFile($name)
  {
    $nameFile = str_concat("-" , get_empresa()->ruc(), $name );
    $this->setNameFile($nameFile);
  }

  /**
   * Devolver el nombre del archivo
   *
   * @return string
   */
  public function getNameFile()
  {
    return $this->nameFile;;
  }

   /**
   * Establecer el nombre del archivo el nombre del archivo
   *
   * @return string
   */
  public function setNameFile($nameFile)
  {
    $this->nameFile = $nameFile;
  }

  /**
   * Devolver el nombre del archivo comprimido
   *
   * @return string
   */
  public function getNameFileZip()
  {
    return $this->getNameFile() . '.zip' ;    
  }


  /**
   * Devolver el nombre del archivo texto
   *
   * @return string
   */
  public function getNameFileTxt()
  {
    return $this->getNameFile() . '.txt' ;
  }  


}

