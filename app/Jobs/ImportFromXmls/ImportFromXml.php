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
  public $cacheTemp;

  public function __construct($pathXmls, Empresa $empresa, $desdeSerie = null, $tipoDocImport = null)
  {

    $this->pathXmls = $pathXmls;
    $this->empresa = $empresa;
    $this->ruc = $empresa->ruc();
    $this->cacheTemp = new CacheTemp();

    // Tipo de documento a importar
    if ($tipoDocImport) {
      $tipoDocImportArr = explode(',', $tipoDocImport);
      $this->tipoDocImport = array_map(function ($item) {
        $arr = explode('-', $item);
        return $arr[0] == "RA"  ?
          sprintf('%s-%s', $this->ruc, $arr[0]) :
          sprintf('%s-%s-%s', $this->ruc, $arr[0], $arr[1]);
      }, $tipoDocImportArr);
    }

    // Desde y hasta
    if ($desdeSerie) {
      $desdeSerie = explode(',', $desdeSerie);
      foreach ($desdeSerie as $item) {
        $itemArr = explode('-', $item);

        $key = $itemArr[1] == "RA" ?
          sprintf('%s-%s', $this->ruc, $itemArr[0]) :
          sprintf('%s-%s-%s', $this->ruc, $itemArr[0], $itemArr[1]);

        $this->desdeRucSerieTipo[$key] = [
          'ruc' => $this->ruc,
          'tipo' => $itemArr[0],
          'serie' => $itemArr[1],
          'numero' => $itemArr[2],
          'completo' => $this->ruc . '-' . $itemArr[0] . '-' . $itemArr[1] . '-' . $itemArr[2],
        ];
      }
    }
  }

  public function getFiles()
  {
    return $this->files;
  }



  public function validateTipoDocImport($file)
  {
    if (!$this->tipoDocImport) {
      return true;
    }

    $found = false;

    foreach ($this->tipoDocImport as $tipoDoc) {
      if (strpos($file, $tipoDoc) !== false) {
        $found = true;
        break;
      }
    }
    return $found;
  }

  public function validateDesdeHasta($file)
  {
    if (count($this->desdeRucSerieTipo) == 0) {
      return true;
    }

    $fileNameDataArr = explode('-', $file);
    $ruc = $fileNameDataArr[0];
    $fileTipo = $fileNameDataArr[1];
    $fileSerie = $fileNameDataArr[2];
    $numero = str_replace('.xml', '', $fileNameDataArr[3]);
    $fileRucTipoSerie = $this->ruc . '-' . $fileTipo . '-' . $fileSerie;
    $fileRucTipoSerieRA = $this->ruc . '-' . $fileTipo;


    // dd($fileTipo, $fileSerie, $numero);

    if ($fileTipo == "RA" || $fileTipo == "RC") {

      if (isset($this->desdeRucSerieTipo[$fileRucTipoSerieRA]) == false) {
        return true;
      }

      $fileNumero = ($fileSerie + $numero);
      $dataDesde = $this->desdeRucSerieTipo[$fileRucTipoSerieRA];
      $desdeNumero = ($dataDesde['serie'] + $dataDesde['numero']);

      return $fileNumero > $desdeNumero;
    }

    if (isset($this->desdeRucSerieTipo[$fileRucTipoSerie]) == false) {
      return true;
    }


    $desdeData = $this->desdeRucSerieTipo[$fileRucTipoSerie];


    return ((int) $numero) > ((int) $desdeData['numero']);
  }

  public function addFile($file)
  {
    if ($file === '.' || $file === '..' || strpos($file, '.xml') === false || strpos($file, $this->ruc) === false) {
      return false;
    }

    // Validar el tipo de documento
    if ($this->validateTipoDocImport($file) == false) {
      return false;
    }

    // Validar el desde y hasta
    if ($this->validateDesdeHasta($file) == false) {
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
        if ($this->addFile($file)) {
          $this->files[$file] = [
            'file' => $file,
            'success' => false,
            'error' => null
          ];
        }
      }
    }

    return $this->files;
  }


  // El motivo por el cual no se modifica el valor del array dentro de la clase es porque,
  // aunque se pasa $file por referencia, en el método handle() se itera sobre $this->files
  // usando foreach ($this->files as $file), lo que en PHP crea una copia del valor del array
  // y no una referencia al elemento original del array. Por lo tanto, cualquier cambio en $file
  // dentro de processFile() no afecta el array $this->files.

  // Para que los cambios se reflejen en el array original, se debe iterar usando referencias:
  // foreach ($this->files as &$file) { ... }

  public function processFile(&$file)
  {
    $creator = $this->getRegisterCreator($file);
    $creator->handle();

    $file['success'] = $creator->isSuccess();

    if ($creator->isError()) {
      $file['error'] = $creator->getResult()->data;
    }

    return $file;
  }

  public function getRegisterCreator($file)
  {
    $fileTipo = explode('-', $file['file'])[1];
    $fileContent = file_get_contents($this->pathXmls . '/' . $file['file']);

    $fileArr = explode('-', $file['file']);
    $fileTipo = $fileArr[1];
    $fileSerie = $fileArr[2];
    $fileNumero = str_replace('.xml', '', $fileArr[3]);


    switch ($fileTipo) {
      case '01':
      case '03':
        return new VentaCreatorFromXml($fileContent, $this->empresa, $this->cacheTemp, $fileTipo, $fileSerie, $fileNumero);
      case '07':
      case '08':
        return new NotaCreatorFromXml($fileContent, $this->empresa, $this->cacheTemp, $fileTipo, $fileSerie, $fileNumero);
      case '09':
        return new GuiaRemisionCreatorFromXml($fileContent, $this->empresa, $this->cacheTemp, $fileTipo, $fileSerie, $fileNumero);
      case 'RA':
      case 'RC':
        return new ResumenCreatorFromXml($fileContent, $this->empresa, $this->cacheTemp, $fileTipo, $fileSerie, $fileNumero);
      default:
    }
  }



  public function handle()
  {
    if (!is_dir($this->pathXmls)) {
      throw new \Exception("The path {$this->pathXmls} is not a directory", 1);
    }

    $this->generateFilesXmls();

    foreach ($this->files as &$file) {
      $this->processFile($file);
    }

    return $this->files;
  }
}
