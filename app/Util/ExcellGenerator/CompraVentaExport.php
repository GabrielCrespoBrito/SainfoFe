<?php
namespace App\Util\ExcellGenerator;

class CompraVentaExport extends ExcellGenerator
{
  const HEADERS = [

    'ventas' => [
      
      'sainfo' => ["AÑO","LIBRO","PERIODO","TIPODOC","SERIE","NUMERO","FECHA","PCRUCC","PCNOMB","PCDIRE","TDOCCODI","BASE","IGV","TOTAL","MONEDA","TOTALD", "TC","ANULADO",	"FREFE",	"TDREFE",	"SREFE", " NREFE" ],
      
      'concar' => [ "VOUCHER", "ANIO", "MES", "FECHA", "TDOC", "SERIE", "NRO_DOC","RUC", "NOMBRE", "FECREG" , "MONEDA", "ANULADA", "EXPORTA", "TOT_DOL", "BASE", "IGV", "TOTAL", "INAFECTA", "TIPO_CAMBIO", "FREFE", "TDREFE",  "SREFE", " NREFE"],

    ],

    'compras' => [

      'sainfo' => ["AÑO", "LIBRO", "PERIODO", "TIPODOC", "SERIE", "NUMERO", "FECHA", "PCRUCC", "PCNOMB", "PCDIRE", "TDOCCODI", "BASE", "IGV", "TOTAL", "MONEDA", "TOT_DOL", "TC"],
      
      'concar' => ["VOUCHER", "ANIO", "MES", "FECHA", "TDOC", "SERIE", "NRO_DOC", "RUC", "NOMBRE", "FECREG", "MONEDA", "TOT_DOL", "BASE", "IGV", "TOTAL", "TIPO_CAMBIO"],
      
    ],

  ];

  const TITLE_HOJA = [
    'sainfo' => "Hoja1",
    'concar' => "CONCAR",
  ];  

  public function __construct($data, $tipo, $para, $customName)
  {    
    parent::__construct($data, true, $customName  );        
    $this->tipo = $tipo;
    $this->para = $para;
  }

  public function getHeader()
  {
    return self::HEADERS[$this->para][$this->tipo];
  }

  public function getSheetTitle()
  {
    return self::TITLE_HOJA[$this->tipo];
  }

  public function procces(&$excel)
  {
      $excel->sheet( $this->getSheetTitle(), function ($sheet) {

        $sheet->row(1, $this->getHeader());       
        foreach ($this->data as $index => $data ) {
          $sheet->row($index + 2, $data);
        }
      });      
  }
}