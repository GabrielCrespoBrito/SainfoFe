<?php

namespace App\Jobs\GuiaSalida;

use DOMDocument;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Log;

class ExtractCDRInfo
{
  public $data = [];
  public $contentZipCdr;
  protected $nameCdr;

  public function __construct($contentZipCdr, $nameCdr)
  { 
    $this->contentZipCdr = $contentZipCdr;
    $this->nameCdr = $nameCdr;
  }

  public function handle()
  {
    return $this->extractInfo();
  }

  public function extractInfo()
  {
    $data = [];

    try {
      $path = getTempPath(time().'zip' , $this->contentZipCdr);
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

      // ResponseCode
      $responseCode = $dom->getElementsByTagName('ResponseCode');
      if ($responseCode->count()) {
        $data['responseCode'] = $responseCode[0]->nodeValue;
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
