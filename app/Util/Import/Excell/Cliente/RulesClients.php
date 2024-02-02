<?php

namespace App\Util\Import\Excell\Cliente;

use App\Producto;
use App\BaseImponible;
use App\ClienteProveedor;
use App\Rules\DocumentoRule;
use App\Ubigeo;
use Illuminate\Validation\Rule;

class RulesClients
{
  const NUMERIC_VALIDATION = ['required', 'numeric', 'min:0'];
  protected $rules_items = [];
  protected $codigos_unico = [];
  protected $codigos_barra = [];
  protected $documentos;
  protected $tipo_cliente;
  protected $tipo_documento;
  protected $vendedores;
  protected $zonas;
  protected $ubigeos;
  protected $procedencias;
  protected $tipoexistencias;

  public function __construct()
  {
    $empresa = get_empresa();
    $this->vendedores = $empresa->vendedores;
    $this->zonas = $empresa->zonas();
    $this->ubigeos = Ubigeo::all()->pluck('ubicodi')->toArray();
  }


  public function setData($tipo_cliente, $tipo_documento)
  {
    $this->tipo_cliente = $tipo_cliente;
    $this->tipo_documento = $tipo_documento;
  }

  public function getTipoCliente()
  {
    return $this->tipo_cliente;
  }

  public function getTipoDocumento()
  {
    return $this->tipo_documento;
  }


  public function updateRules()
  {
    // $this->rules_items['codigo_unico'] = $this->getCodigoUnicoRule();
    // $this->rules_items['codigo_barra'] = $this->getCodigoBarraRule();
  }

  public function addDocumento($documento)
  {
    $this->documentos[] = $documento;
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

  public function getUbigeoRule()
  {
    return [
      'nullable',
      Rule::in($this->ubigeos)
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

  public function getDocumentoRule()
  {
    return [new DocumentoRule( $this->documentos, $this->tipo_cliente, $this->tipo_documento ) ];
  }

  public function getCodigoBarraRule()
  {
    return ['nullable', 'sometimes', 'max:120', Rule::notIn($this->codigos_barra)];
  }

  public function searchCodigos()
  {
    // $this->documentos = ClienteProveedor::pluck('TipCodi', 'PCRucc')->toArray();
    // $this->documentos = ClienteProveedor::all('PCRucc', 'TipCodi');
    $this->documentos = ClienteProveedor::all('PCRucc', 'TipCodi')->mapWithKeys(function ($cliente, $key) {
      $key = $cliente->TipCodi . '-' . $cliente->PCRucc;
      return [ $key => $cliente ];
    })->toArray();
    
    // _dd( "chambeador", $this->documentos );
    // exit();
    // $this->documentos = $codigos->keys()->toArray();
    // _dd(  $this->documentos );
  }

  public function generateRules()
  {
    $this->searchCodigos();

    $this->rules_items = [
      'tipo_cliente' => $this->getRequiredString('C,P'),
      'tipo_documento' => $this->getRequiredString('0,1,6'),
      'documento' => $this->getDocumentoRule(),
      'nombre' => 'required|min:2|max:255',
      'direccion' => 'nullable|min:1|max:150',
      'telefono' => 'nullable|min:1|max:100',
      'correo' => 'nullable|email',
      'ubigeo' =>  $this->getUbigeoRule(), 
      'zona' => 'nullable|min:2|max:100',
      'cvend' => 'nullable|max:4',
      'vendedor' => 'required_without:cvend',

      // 'codigo_barra' => $this->getCodigoBarraRule(),
      // 'categoria' =>  $this->getCategoryRule(),
      // 'unidad' => $this->getUnidadRule(),
      // 'moneda' => $this->getRequiredString('Soles,Dolares'),
      // 'costo_dolares' => $nv,
      // 'costo_soles' => $nv,
      // 'margen' => $nv,
      // 'precio_soles' => $nv,
      // 'precio_dolares' => $nv,
      // 'peso' => $nv,
      // 'base_igv' => $this->getBasesIGV(),
      // 'incluye_igv' => $this->getRequiredString('Si,No'),
      // 'tipo_existencia' =>$this->getTipoExistenciaRule(),
    ];
  }
}
