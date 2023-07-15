<?php

namespace App\Jobs\Venta;

use App\Resumen;
use App\ResumenDetalle;

class validateResumen
{
  public $pathXml;
  public $fecha_envio;
  public $empresa;
  public $empresa_id;
  public $user;
  public $loccodi;
  public $resumen;
  public $xml;
  public $currentBoleta;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($pathXml )
  {
    /* Upload test   */
    $this->fecha_envio = date ("Y-m-d H:i:s.", filemtime($pathXml));
    $this->pathXml = $pathXml;

    if (!file_exists($this->pathXml)) {
      throw new \Exception("The xml {$this->pathXml} is not found", 1);
    }

    $this->xml = simplexml_load_file($this->pathXml, 'SimpleXMLElement', LIBXML_NOCDATA);
  }


  public function getNodo($nodoPath)
  {
    return $this->xml->xpath($nodoPath);
  }

  public function getNodoSingleValue($nodoPath)
  {
    $simpleXmlElement = $this->getNodo($nodoPath);

    if (count($simpleXmlElement)) {
      return ((array) $simpleXmlElement[0])[0];
    }

    return null;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $resumen_id = $this->getNodoSingleValue('//cbc:ReferenceID');
    $ticket = $this->getNodoSingleValue('//cbc:ID');
    $resumen = Resumen::where('DocNume', $resumen_id)->first();
    $resumen->DocTicket = $ticket;
    $resumen->DocCHash = $ticket;
    $resumen->save();
  }
}
