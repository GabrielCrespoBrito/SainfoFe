<?php

namespace App\Util\ExcellGenerator;

abstract class ExcellGenerator
{
  # Documento generador
  public $document;

  # Información con la que trabajar
  protected $data;

  # Información de la data al guardarse
  protected $infoStore;  

  # Empresa actual que se va a exportar la data
  protected $empresa;

  # Si la exportación va hacer en excell
  protected $inExcel;

  # Nombre del archivo
  protected $customName = false;

  # Titulo del archivo
  protected $title = "";

  public function __construct( $data ,  bool $inExcel = true, string $customName = null )
  {
    $this->data = $data;
    $this->empresa = get_empresa();
    $this->inExcel = $inExcel;
    $this->customName = $customName;
  }

  /**
   * Titulo del archivo
   * 
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Devolver extension que se va a exportar
   * 
   * @return string
   */
  public function getExtention($withPoint = true )
  {
    $extention = $this->inExcel ? 'xlsx' : 'pdf';
    return  $withPoint ? '.' . $extention : $extention;
  }

  /**
   * Nombre del archivo a exportar
   * @example 20569854_20190801 | 20569854_20190801;
   * 
   * @return string
   */
  public function getNameFile()
  {
    return $this->customName ?? sprintf('%s_%s' , $this->empresa->EmpLin1 , time()
    );

  }

  /**
   * Generar el archivo 
   * 
   * @return 
   */
  public function generate() 
  {
    $this->document = \Excel::create($this->getNameFile(), function($excel){
      $this->procces( $excel );
    });

    return $this;
  }

  /**
   * Guardar el archivo generado
   * @param bool|string $path - Ubicación donde guardar, si es falso guardara en storage/exports
   * @param bool $fullInformation - Para devolver información asociada al guardado
   * 
   * @return array|null
   */
  public function store($path = false, bool $fullInformation = true)
  {
    return $this->infoStore = $this->document->store($this->getExtention(false) , $path , $fullInformation );
  }

  /**
   * Exportar
   * 
   * @return void
   */
  public function export()
  { 
    $this->document->export($this->getExtention(false));

    return $this;
  }

}