<?php

namespace App\Util\Import\Excell\Producto;

use App\Producto;
use App\BaseImponible;
use Illuminate\Validation\Rule;

class RulesItems
{
  const NUMERIC_VALIDATION = ['required', 'numeric', 'min:0'];
  protected $rules_items = [];
  protected $codigos_unico = [];
  protected $codigos_barra = [];
  protected $unidades;
  protected $procedencias;
  protected $tipoexistencias;

  // Regular Expresiòn to validate 2
  // 'costo_estimado_repuesto' => ['required', 'regex:^(?:[1-9]\d+|\d)(?:\,\d\d)?$' ],

  public function __construct()
  {
    $this->unidades = cacheHelper('unidadproducto.all');
    $this->procedencias = cacheHelper('procendencia.all');
    $this->tipoexistencias = cacheHelper('tipoexistencia.all');
  }

  public function updateRules()
  {
    $this->rules_items['codigo_unico'] = $this->getCodigoUnicoRule();
    $this->rules_items['codigo_barra'] = $this->getCodigoBarraRule();
  }

  public function setCodigos($codigo_unico , $codigo_barra = null)
  {
    $this->codigos_unico[] = $codigo_unico;

    if( $codigo_barra ){
      $this->codigos_barra[] = $codigo_barra;
    }
  }

  public function getRequiredString( $aditional )
  {
    return 'required|in:' . $aditional;
  }

  public function getBasesIGV() 
  {
    $bases = sprintf('%s,%s,%s,%s',
      BaseImponible::GRAVADA,
      BaseImponible::INAFECTA,
      BaseImponible::EXONERADA,
      BaseImponible::GRATUITA
    );

    return $this->getRequiredString($bases);
  }

  public function getUnidadRule()
  {
    $unidades = $this->unidades->pluck('UnPNomb')->toArray();

    return [
      'required',
      Rule::in($unidades)
    ];

  }


  public function getCategoryRule()
  {
    // @TODO: hacer expresión regular que evalue, que no existan mas de dos(2) simbolo mayor que (>)
    return [
      'required', 
      'max:250'
    ];
  }
  
  public function getRules()
  {
    if( $this->rules_items  ){
      $this->updateRules();
    }
    else {
      $this->generateRules();
    }
    return $this->rules_items;
  }

  public function getTipoExistenciaRule()
  {
    $tipoexistencias = $this->tipoexistencias->pluck('TieNomb')->implode(',');
    return $this->getRequiredString($tipoexistencias);
  }

  public function getOrSearchCodigosBarras()
  {
  }

  public function getCodigoUnicoRule()
  {
    return ['required', 'max:120', Rule::notIn($this->codigos_unico)];
  }


  public function getCodigoBarraRule()
  {
    return ['nullable', 'sometimes', 'max:120', Rule::notIn($this->codigos_barra)];
  }


  public function searchCodigos()
  {
    $codigos = Producto::pluck('ProCodi1', 'ProCodi');
    $this->codigos_unico = $codigos->keys()->toArray();
    $this->codigos_barra = remove_empty_arr($codigos->values()->toArray());
  }

  public function generateRules()
  {
    $nv = self::NUMERIC_VALIDATION;
    
    $this->searchCodigos();

    $this->rules_items = [
      'codigo_unico' => $this->getCodigoUnicoRule(),
      'codigo_barra' => $this->getCodigoBarraRule(),
      'categoria' =>  $this->getCategoryRule(),
      'marca' => 'required|min:1|max:120',
      'unidad' => $this->getUnidadRule(),
      'nombre' => 'required|min:1|max:255',
      'moneda' => $this->getRequiredString('Soles,Dolares'),
      'costo_dolares' => $nv,
      'costo_soles' => $nv,
      'margen' => $nv,
      'precio_soles' => $nv,
      'precio_dolares' => $nv,
      'peso' => $nv,
      'base_igv' => $this->getBasesIGV(),
      'incluye_igv' => $this->getRequiredString('Si,No'),
      'tipo_existencia' =>$this->getTipoExistenciaRule(),
    ];
  }
}
