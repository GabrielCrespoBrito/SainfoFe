<?php

namespace App\Helpers;

use App\Venta;
use App\VentaAmazon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FHelper
{
  public $paths;
  public $is_online;
  public $save_amazon = false;
  public $ruc;
  public $codigo;

  public $only_nube = false;
  const NUBE = 'nube';
  const LOCAL = 'local';
  const DATA = 'data';
  const CERT = 'cert';
  const ENVIO = 'envio';
  const IMG = 'img';
  const CDR  = 'cdr';
  const PDF  = 'pdf';
  const DB = "DB";

  /*
    --- name: Nombre del archivo
    --- sitio: Carpeta a guardar (data,envio,cdr,pdf)
    --- content: Contenido del archivo
  */
  public function save($name, $sitio, $content)
  {
    // Guardar en local
    if (!$this->is_online && !$this->only_nube) {
      $path = $this->getPath(self::LOCAL, $sitio, $name);
      $this->save_local($path, $content);
    }

    // Guardar en la nube 
    if ($this->is_online || $this->save_amazon || $this->only_nube) {
      $path = $this->getPath(self::NUBE, $sitio, $name);
      $this->save_nube($path, $content);
    }

    return $path;
  }

  /**
   * Borrar archivos
   * 
   * @param $name 
   * 
   * @return bool
   */
  /*
    --- Name: Nombre del archivo
    --- sitio: Carpeta a guardar (data,envio,cdr,pdf)
  */
  public function delete($name, $sitio)
  {
    // Guardar en local
    if (!$this->is_online && !$this->only_nube) {
      $path = $this->getPath(self::LOCAL, $sitio, $name);
      $this->delete_local($path);
    }

    // Guardar en la nube 
    if ($this->is_online || $this->save_amazon || $this->only_nube) {
      $path = $this->getPath(self::NUBE, $sitio, $name);
      $this->delete_nube($path);
    }

    return $path;
  }

  public function deleteCDR($name)
  {
    $this->delete($name, self::CDR);
  }

  public function deletePDF($name)
  {
    $this->delete($name, self::PDF);
  }

  public function deleteEnvio($name)
  {
    $this->delete($name, self::ENVIO);
  }


  public function deleteData($name)
  {
    $this->delete($name, self::DATA);
  }

  public function delete_img($name)
  {
    $this->delete($name, self::IMG);
  }

  public function getTempPath($name)
  {
    return public_path('temp' . getSeparator()) . $name;
  }

  public function saveTemp($content, $name = "documento.pdf")
  {
    $path = $this->getTempPath($name);
    \File::put($path, $content);
    return $path;
  }


  public function file_exists($path)
  {
    if ($this->is_online) {
      return $this->exists_nube($path);
    }
    return $this->exists_local($path);
  }

  public function exists_nube($path)
  {
    return \Storage::disk('s3')->exists($path);
  }
  public function exists_local($path)
  {
    return file_exists($path);
  }



  public function delete_local($path)
  {
    \File::delete($path);
  }

  public function delete_nube($path)
  {
    return \Storage::disk('s3')->delete($path);
  }

  public function save_local($path, $content)
  {
    \File::put($path, $content);
  }

  public function save_nube($path, $content)
  {
    return \Storage::disk('s3')->put($path, $content);
  }

  public function getCompletePathL($folder)
  {
    $separator = getSeparator();
    
    $empresa_id = $this->codigo . '-' .  $this->ruc;

    return sprintf(
      '%s%s%s%s',
      get_setting('carpeta_guardado'),
      $empresa_id,
      $separator,
      $folder
    );


  }

  public function setAllPaths()
  {
    $this->paths = [
      self::LOCAL => [
        // 'data'  => $this->getCompletePathL('data_path'),
        // 'envio' => $this->getCompletePathL('envio_path'),
        // 'cdr'   => $this->getCompletePathL('cdr_path'),
        // 'cert'  => $this->getCompletePathL('cert_path'),
        // 'pdf'   => $this->getCompletePathL('pdf_path'),
        // 'img'   => $this->getCompletePathL('img_path'),

        'data'  => $this->getCompletePathL('XMLData'),
        'envio' => $this->getCompletePathL('XMLEnvio'),
        'cdr'   => $this->getCompletePathL('XMLCDR'),
        'cert'  => $this->getCompletePathL('XMLCert'),
        'pdf'   => $this->getCompletePathL('XMLPDF'),
        'img'   => $this->getCompletePathL('images'),
      ],
      self::NUBE => [
        'data'  => config('app.path_archivos.xml_data'),
        'envio' => config('app.path_archivos.xml_envio'),
        'cdr'   => config('app.path_archivos.xml_cdr'),
        'cert'  => config('app.path_archivos.cert'),
        'pdf'   => config('app.path_archivos.xml_pdf'),
        'img'   => config('app.path_archivos.images'),
      ]
    ];
  }

  public function __construct($ruc, $codigo = '')
  {
    
    $this->is_online   = is_online();
    $this->save_amazon = hay_internet() ? get_setting('save_amazon') : false;
    $this->ruc = is_null($ruc) ? get_ruc() : $ruc;
    $this->codigo = $codigo ? $codigo : get_codigo();
    
    $this->setAllPaths();
    
    foreach ($this->paths[self::NUBE] as $name => $value) {
      $separador = getSeparator();
      $path = implode($separador, $value);
      $newPath = str_replace("{ruc_cliente}", $this->ruc, $path);
      $this->paths[self::NUBE][$name] = $newPath;
    }
  }

  public function getPath($ambito, $sitio, $name)
  {
    return $this->paths[$ambito][$sitio] . getSeparator() .  $name;
  }

  public function deleteAllInfo()
  {
    $excepts_folder = [self::IMG, self::CERT];
    $is_local = !$this->is_online && !$this->only_nube;
    $paths = $is_local ? $this->paths['local'] : $this->paths['nube'];
    foreach ($paths as $folder => $path_original) {
      if (!in_array($folder, $excepts_folder)) {
        $path =  $path_original . "/*";
        if ($is_local) {
          array_map('unlink', array_filter((array) glob($path)));
        } else {
          $files = Storage::disk('s3')->files($path_original);
          foreach ($files as $file) {
            $this->delete_nube($file);
          }
        }
      }
    }
  }

  public function getFileBySite($sitio, $name)
  {
    return $this->getFile($this->getPath($this->getAmbito(), $sitio, $name));
  }

  public function getFile($path = "")
  {
    if ($this->is_online) {
      return $this->getFileInNube($path);
    }
    return $this->getFileInLocal($path);
  }

  public function getFileInLocal($path)
  {
    return file_get_contents($path);
  }

  public function getFileInNube($path)
  {
    return Storage::disk('s3')->get($path);
  }

  public function getNameCert($ext)
  {
    return $this->ruc . $ext;
  }

  public function save_data($name, $content)
  {
    $this->save($name, self::DATA, $content);
  }

  public function save_envio($name, $content)
  {
    $this->save($name, self::ENVIO, $content);
  }

  public function save_cdr($name, $content)
  {
    $this->save($name, self::CDR, $content);
  }
  public function save_img($name, $content)
  {
    $this->save($name, self::IMG, $content);
  }

  public function save_pdf($name, $content)
  {
    return $this->save($name, self::PDF, $content);
  }
  public function save_db($name, $content)
  {
    $this->save($name, self::DB, $content);
  }
  public function save_cert($ext, $content)
  {
    $this->save($this->getNameCert($ext), self::CERT, $content);
  }

  public function setOnlyNube($resp = true)
  {
    $this->only_nube = $resp;
  }

  public function getAmbito()
  {
    return $this->is_online ? self::NUBE : self::LOCAL;
  }

  public function exists($sitio, $name)
  {
    return $this->file_exists($this->getPath($this->getAmbito(), $sitio, $name));
  }

  public function existsInLocal($sitio, $name)
  {
    return $this->exists_local($this->getPath(self::LOCAL, $sitio, $name));
  }

  public function existsInNube($sitio, $name)
  {
    return $this->exists_nube($this->getPath(self::NUBE, $sitio, $name));
  }

  public function certExist($ext)
  {
    return $this->file_exists($this->getPath($this->getAmbito(), self::CERT, $this->getNameCert($ext)));
  }

  public function pdfExist($name)
  {
    return $this->file_exists($this->getPath($this->getAmbito(), self::PDF, $name));
  }

  public function imgExist($name)
  {
    return $this->file_exists($this->getPath($this->getAmbito(), self::IMG, $name));
  }

  public function xmlExist($name)
  {
    return $this->file_exists($this->getPath($this->getAmbito(),  self::ENVIO, $name));
  }

  public function cdrExist($name)
  {
    return $this->file_exists($this->getPath($this->getAmbito(), self::CDR, $name));
  }

  public function getCertPath($ext)
  {
    return $this->getPath($this->getAmbito(), self::CERT, $this->getNameCert($ext));
  }

  public function getPdf($name)
  {
    return $this->getFile($this->getPath($this->getAmbito(), self::PDF, $name));
  }

  public function getEnvio($name)
  {
    return $this->getFile($this->getPath($this->getAmbito(), self::ENVIO, $name));
  }

  public function getCert($ext)
  {
    return $this->getFile($this->getPath($this->getAmbito(), self::CERT, $this->getNameCert($ext)));
  }

  public function getCdr($name)
  {
    return $this->getFile($this->getPath($this->getAmbito(), self::CDR, $name));
  }

  public function getFilesInLocal($sitio, $name)
  {
    return $this->getFile($this->getPath(self::LOCAL, $sitio, $name));
  }

  public function getFilesInNube($sitio, $name)
  {
    return $this->getFileInNube($this->getPath(self::NUBE, $sitio, $name));
  }


  public function saveVenta($id_venta, $is_new = true)
  {
    $this->only_nube = true;
    $v = Venta::find($id_venta);


    if ($v->isBoleta()) {
      $detalle_resumen = $v->anulacion;

      if (is_null($detalle_resumen)) {
        return;
      } else {
        $is_new = is_null($v->amazon);
        $resumen = $detalle_resumen->resumen;
        $nameCdr = $resumen->nameFile(true, '.zip');
        $nameEnvio = $resumen->nameFile(false, '.zip');
        $namePDF = $resumen->nameFile(false, '.pdf');
      }
    } else {
      $is_new = is_null($v->amazon);
    }

    if ($is_new) {

      $data = ['VtaOper' => $id_venta, 'PDF' => 0, 'CDR' => 0, 'XML' => 0, 'Estatus' => 0, 'EmpCodi', $v->EmpCodi];

      $amazon = new VentaAmazon();

      $cdrExists = $v->fileExist(self::CDR);

      if ($cdrExists['success']) {

        $this->save_cdr($cdrExists['nameFile'], $this->getFilesInLocal(self::CDR, $cdrExists['nameFile']));
        $data['CDR'] = 1;

        $pdfExists = $v->fileExist(self::PDF);
        if ($pdfExists['success']) {
          $this->save_pdf($pdfExists['nameFile'], $this->getFilesInLocal(self::PDF, $pdfExists['nameFile']));
          $data['PDF'] = 1;
        }

        $envioExist = $v->fileExist(self::ENVIO);
        if ($envioExist['success']) {
          $this->save_envio($envioExist['nameFile'], $this->getFilesInLocal(self::ENVIO, $envioExist['nameFile']));
          $data['XML'] = 1;
        }
      }

      $amazon->fill($data);
      $amazon->save();
      $amazon->updatedstatus();
      $amazon->updateOthers();
    }

    // Falta por guardase algun archivo
    else {

      $amazon = $v->amazon;

      // CDR
      if (!$amazon->CDR) {
        $cdrExists = $v->fileExist(self::CDR);
        if ($cdrExists['success']) {
          $r = $this->save_cdr($cdrExists['nameFile'], $this->getFilesInLocal(self::CDR, $cdrExists['nameFile']));
          $amazon->update(['CDR' => 1]);
        }
      }


      if (!$amazon->PDF) {

        $pdfExists = $v->fileExist(self::PDF);

        if ($pdfExists['success']) {
          $this->save_pdf($pdfExists['nameFile'], $this->getFilesInLocal(self::PDF, $pdfExists['nameFile']));
          $amazon->update(['PDF' => 1]);
        }
      }

      if (!$amazon->XML) {
        $envioExist = $v->fileExist(self::ENVIO);

        if ($envioExist['success']) {
          $this->save_envio($envioExist['nameFile'], $this->getFilesInLocal(self::ENVIO, $envioExist['nameFile']));
          $amazon->update(['XML' => 1]);
        }
      }

      $amazon->updatedStatus();
    }
  }


  /**
   * Comprimir un archivo en formato .zip
   * @param string $pathFileToCompress 
   * @param null|string $pathCompress
   * @param null|string $nameFile
   * 
   * @return string
   */
  public function compress($pathFileToCompress, $pathCompress = null, $nameFile = null)
  {
    // Si no se pasa la ubicación del archivo donde guardar, se guardara en la carpeta temp.
    $nameCompress = ($nameFile ?? time()) . '.zip';
    $pathCompress = $pathCompress ? $pathCompress : (public_path('temp/') . $nameCompress);

    $zipper = \Zipper::make($pathCompress);
    $zipper->add($pathFileToCompress);
    $zipper->close();

    return [
      'path' => $pathCompress,
      'name' => $nameCompress
    ];
  }
}
