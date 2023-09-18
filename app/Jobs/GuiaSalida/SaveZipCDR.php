<?php

namespace App\Jobs\GuiaSalida;

use DOMDocument;
use App\GuiaSalida;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Log;

class SaveZipCDR
{
  public $guiaSalida;
  public $data = [];
  public $ruc;
  public $base64ZipCdr;
  public $contentZip;
  protected $nameFile;
  protected $nameCdr;
  protected $nameZipCdr;

  public function __construct(GuiaSalida $guiaSalida, $base64ZipCdr,  &$data = [])
  {
    $this->ruc = get_empresa('EmpLin1');
    $this->guiaSalida = $guiaSalida;
    $this->base64ZipCdr = $base64ZipCdr;
    $this->nameFile = sprintf(
      'R-%s-%s-%s-%s',
      $this->ruc,
      $this->guiaSalida->getTipoDocumento(),
      $this->guiaSalida->GuiSeri,
      $this->guiaSalida->GuiNumee
    );
    $this->nameCdr = $this->nameFile . '.xml';
    $this->nameZipCdr = $this->nameFile . '.zip';
    $this->data = $data;
  }

  public function handle()
  {
    $this->contentZip = base64_decode($this->base64ZipCdr);
    FileHelper($this->ruc)->save_cdr( $this->nameZipCdr, $this->contentZip );

    return $this->extractInfo();
  }

  public function extractInfo()
  {
    $data = [];

    try {
      $path = getTempPath(time().'zip' , $this->contentZip);
      $zipper = new Zipper();
      $zipper->make($path);
      $content = $zipper->getFileContent($this->nameCdr);
  
      $dom = new DOMDocument;
      $dom->loadXML($content);
  
      // Extrañer CDR
      $qrLink = $dom->getElementsByTagName('DocumentDescription');
      if($qrLink->count()){
        $data['qrLink'] = $qrLink[0]->nodeValue;
      }
  
      // Extrañer CDR
      $qrLink = $dom->getElementsByTagName('DocumentDescription');
      if ($qrLink->count()) {
        $data['qrLink'] = $qrLink[0]->nodeValue;
      }
  
      // Observaciones
      $observaciones = $dom->getElementsByTagName('Note');
      if ($observaciones->count()) {
        $data['observaciones'] = [];
        foreach( $observaciones as $observacion ){
          $data['observaciones'][] = $observacion->nodeValue; 
        }
      }
  
      // Observaciones
      $descripcion = $dom->getElementsByTagName('Description');
      if ($descripcion->count()) {
        $data['descripcion'] = $descripcion[0]->nodeValue;
      }
      //code...
    } catch (\Throwable $th) {
      Log::error($th->getMessage());
    }

    return $data;
  }


}
