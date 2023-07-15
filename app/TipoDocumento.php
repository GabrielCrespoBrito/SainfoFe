<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
  protected $connection = 'mysql';
  protected $table = 'prov_clientes_tipo_doc';  
  protected $timetamps = false;
  protected $keyType = 'string';     
  protected $primaryKey = 'TipDocCodi';

  const NINGUNA = 0;
  const DNI = 1;
  const CARNETA_EXTRANJERIA = 4;
  const RUC = 6;
  const PASAPORTE = 7;
  const CEDULA = "B";


  const TRANSPORTISTAS = [
    '0' => 'DOC.TRIB.NO.DOM.SIN.RUC',
    '1' => 'Documento Nacional de Identidad',
    '4' => 'Carnet de extranjería',
    '7' => 'Pasaporte',
    'A' => 'Cédula Diplomática de identidad',
    'B' => 'DOC.IDENT.PAIS.RESIDENCIA-NO.D',
    'C' => 'Tax Identification Number - TIN – Doc Trib PP.NN',
    'D' => 'Identification Number - IN – Doc Trib PP. JJ',
    'E' => 'TAM- Tarjeta Andina de Migración ',
    'F' => 'Permiso Temporal de Permanencia - PTP',
    'G' => 'Salvoconducto',
  ];
  
  const NOMBRES = 
  [    
	  self::NINGUNA =>"NINGUNA",
	  self::DNI =>"DNI",
    self::RUC => "RUC",
    self::CARNETA_EXTRANJERIA => "CARNETA_EXTRANJERIA",
	  self::PASAPORTE =>"PASAPORTE",
	  self::CEDULA => "CEDULA "
  ];

  const NOMBRES_LECTURA =
  [
    self::NINGUNA => "NINGUNO",
    self::DNI => "DNI",
    self::CARNETA_EXTRANJERIA => "CARNET DE EXTRANJERIA",
    self::RUC => "RUC",
    self::PASAPORTE => "PASAPORTE",
    'A' => 'Cédula Diplomática de identidad',
    self::CEDULA => "DOC.IDENT",
    'C' =>  'Tax Identification Number - TIN – Doc Trib PP.NN',
    'D' =>  'Identification Number - IN – Doc Trib PP. JJ',
    'E' =>  'TAM- Tarjeta Andina de Migración ',
    'F' =>  'Permiso Temporal de Permanencia - PTP',
    'G' =>  'Salvoconducto',
  ];


  const NINGUNA_NOMBRE = self::NOMBRES[self::NINGUNA];

  public static function getNombre($codigo)
  {
    return self::NOMBRES[$codigo];
  }

  public static function getNombreReporte($codigo)
  {
   return  [
    self::NINGUNA => "S/D",
    self::DNI => "DNI",
    self::RUC => "RUC",
    self::CARNETA_EXTRANJERIA => "C.E",
    self::PASAPORTE => "PAS.",
    self::CEDULA => "DOC.IDENT",
    'C' =>	'Tax Identification Number - TIN – Doc Trib PP.NN',
    'D' =>	'Identification Number - IN – Doc Trib PP. JJ',
    'E' =>	'TAM- Tarjeta Andina de Migración ',
    'F' =>	'Permiso Temporal de Permanencia - PTP',
    'G' =>	'Salvoconducto',

// 0	DOC.TRIB.NO.DOM.SIN.RUC
// 1	Documento Nacional de Identidad
// 4	Carnet de extranjería
// 6	Registro Unico de Contributentes
// 7	Pasaporte
// A	Cédula Diplomática de identidad
// B	DOC.IDENT.PAIS.RESIDENCIA-NO.D
// C	Tax Identification Number - TIN – Doc Trib PP.NN
// D	Identification Number - IN – Doc Trib PP. JJ
// E	TAM- Tarjeta Andina de Migración 
// F	Permiso Temporal de Permanencia - PTP
// G	Salvoconducto
  ][$codigo];

  }



  public static function getNombreLectura( $codigo )
  {
    if ( $codigo == self::NINGUNA ) {
      return 'Doc.';
    }

    return self::NOMBRES_LECTURA[$codigo];
  }


  public static function getAll()
  {
    return [
      self::NINGUNA,
      self::DNI,
      self::RUC,
      self::PASAPORTE,
      self::CARNETA_EXTRANJERIA,
      self::CEDULA
    ];
  }


}


