<?php

namespace App\Util\Import\Excell\Producto;

use App\Producto;
use App\BaseImponible;
use App\Models\Sunat\SunatProducto;
use Illuminate\Validation\Rule;

class RulesItems
{
  const NUMERIC_VALIDATION = ['required', 'numeric', 'min:0'];
  protected $rules_items = [];
  protected $codigos_unico = [];
  protected $codigos_barra = [];
  protected $unidades;
  protected $unidades_rule = null;
  protected $sunat_productos_codigos;
  protected $sunat_productos_codigos_rule = null;
  protected $procedencias;
  protected $tipoexistencias;

  // Regular Expresiòn to validate 2
  // 'costo_estimado_repuesto' => ['required', 'regex:^(?:[1-9]\d+|\d)(?:\,\d\d)?$' ],

  public function __construct()
  {
    $unidades = cacheHelper('unidadproducto.all');
    $this->procedencias = cacheHelper('procendencia.all');
    $this->tipoexistencias = cacheHelper('tipoexistencia.all');
    $sunat_productos_codigos = cacheHelper('sunat.productos.all');

    $this->unidades_rule = [
      'required',
      Rule::in($unidades->pluck('UnPCodi')->toArray())
    ];

    $this->sunat_productos_codigos_rule = [
      'sometimes',
      'nullable',
      'min:8',
      'max:8',
      Rule::in($sunat_productos_codigos->pluck('clase_id')->toArray())
    ];

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

  public function getCodigoSunatRule()
  {
    return $this->sunat_productos_codigos_rule;
  }

  public function getUnidadRule()
  {
    return $this->unidades_rule;
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
    if( $this->rules_items ){
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
      'codigo_sunat' => $this->getCodigoSunatRule(),
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
