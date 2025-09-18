<?php

namespace App\Jobs\ImportFromXmls;

use App\Empresa;


class ImportFromXml
{
  public $pathXmls;
  public $empresa;
  public $ruc;
  public $desdeRucSerieTipo = [];
  public $tipoDocImport;
  public $files = [];

  public function __construct( $pathXmls, Empresa $empresa, $desdeSerie = null, $tipoDocImport = null )
  {

    $this->pathXmls = $pathXmls;
    $this->empresa = $empresa;
    $this->ruc = $empresa->ruc();

    // Tipo de documento a importar
    if( $tipoDocImport ){
      $tipoDocImportArr = explode(',', $tipoDocImport);
      $this->tipoDocImport = array_map(function($item){
        $arr = explode('-', $item);
        return $arr[0] == "RA"  ? 
          sprintf('%s-%s', $this->ruc, $arr[0]) : 
          sprintf('%s-%s-%s', $this->ruc, $arr[0], $arr[1]);
      }, $tipoDocImportArr);
    }

    // Desde y hasta
    if( $desdeSerie ){
      $desdeSerie = explode(',', $desdeSerie);
      foreach( $desdeSerie as $item ){
        $itemArr = explode('-', $item);

        $key = $itemArr[1] == "RA" ? 
          sprintf('%s-%s', $itemArr[0], $itemArr[1]) : 
          sprintf('%s-%s-%s', $itemArr[0], $itemArr[1], $itemArr[2]);
          
        $this->desdeRucSerieTipo[ $key ] = [
          'ruc' => $itemArr[0],
          'tipoSerie' => $itemArr[1],
          'serie' => $itemArr[2],
          'numero' => $itemArr[3],
          'completo' => $itemArr[0] . '-' . $itemArr[1] . '-' . $itemArr[2] . '-' . $itemArr[3],
        ];
      }
    }
  }

  // public function getCreator($tidCodi)
  // {
  //   switch( $tidCodi ){
  //     case '01':
  //       return new VentaCreatorFromXml( $fileContent, $this->empresa );
  //     case '02':
  //       return new GuiaRemisionCreatorFromXml( $fileContent, $this->empresa );
  //   }
  // }
  

  public function getFiles()
  {
    return $this->files;
  }



  public function validateTipoDocImport($file)
  {
    if( !$this->tipoDocImport ){
      return true;
    }

    $found = false;

    foreach( $this->tipoDocImport as $tipoDoc ){
      if( strpos($file, $tipoDoc) !== false ){
        $found = true;
        break;
      }
    }
    return $found;
  }

  public function validateDesdeHasta($file)
  {
    if( count($this->desdeRucSerieTipo) == 0 ){
      return true;
    }

    $fileNameDataArr = explode('-', $file);
    $ruc = $fileNameDataArr[0];
    $fileTipo = $fileNameDataArr[1];
    $fileSerie = $fileNameDataArr[2];
    $numero = str_replace('.xml', '', $fileNameDataArr[3]);
    $fileRucTipoSerie = $ruc . '-' . $fileTipo . '-' . $fileSerie;
    $fileRucTipoSerieRA = $ruc . '-' . $fileTipo;


    if( $fileTipo == "RA" || $fileTipo == "R" ){
      
      if( isset($this->desdeRucSerieTipo[ $fileRucTipoSerieRA ]) == false ){
        return true;
      }
      
      $fileNumero = ($fileSerie + $numero);
      $dataDesde = $this->desdeRucSerieTipo[ $fileRucTipoSerieRA ];
      $desdeNumero = ($dataDesde['serie'] + $dataDesde['numero']);

      return $fileNumero > $desdeNumero;
    }



    if(  isset($this->desdeRucSerieTipo[ $fileRucTipoSerie ]) == false ){
      return true;
    }


    $desdeData = $this->desdeRucSerieTipo[ $fileRucTipoSerie ];


    return ((int) $numero) > ((int) $desdeData['numero']);
  }

  public function addFile($file)
  {
    // $file = str_replace('.xml', '', $file);
    if ($file === '.' || $file === '..' || strpos($file, '.xml') === false || strpos($file, $this->ruc) === false) {
      return false;
    }

    // Validar el tipo de documento
    if( $this->validateTipoDocImport($file) == false ){
      return false;
    }

    // Validar el desde y hasta
    if( $this->validateDesdeHasta($file) == false ){
      return false;
    }

    return true;
  }

  public function generateFilesXmls()
  {
    $directory = $this->pathXmls;

    $dirs = array_chunk(scandir($directory), 500);

    foreach ($dirs as $dir) {
      foreach ($dir as $file) {
        if($this->addFile($file)){
          logger("File Valid: " . $file );
          $this->files[$file] = [
            'file' => $file,
            'success' => false,
            'errors' => null
          ];
        }
      }
    }

    return $this->files;
  }


  public function processFile($file)
  {
    $creator = $this->getRegisterCreator($file);
    
    $creator->handle();

    $res = $creator->getResult();

    $file['success'] = $res->success;
    $file['errors'] = $res->errors;

    return $file;
  }

  public function getRegisterCreator($file)
  {
    $fileTipo = explode('-', $file['file'])[1];
    $fileContent = file_get_contents($this->pathXmls . '/' . $file['file']);

    switch( $fileTipo ){
        case '01':
        case '03':
          return new VentaCreatorFromXml($fileContent, $this->empresa);
        case '07':
        case '08':
          return new NotaCreatorFromXml($fileContent, $this->empresa);
        case '09':
          return new GuiaRemisionCreatorFromXml($fileContent, $this->empresa);
        case 'RA':
        case 'RC':
          return new ResumenCreatorFromXml($fileContent, $this->empresa);
        default:
    }
  }



  public function handle()
  {
    if( !is_dir($this->pathXmls) ){
      throw new \Exception("The path {$this->pathXmls} is not a directory", 1);
    }

    $this->generateFilesXmls();

    foreach( $this->files as $file ){
      $this->processFile($file);
    }
    
  }
}

