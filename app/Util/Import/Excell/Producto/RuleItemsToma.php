<?php

namespace App\Util\Import\Excell\Producto;

use App\Producto;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RuleItemsToma
{
  const NUMERIC_VALIDATION = ['required', 'numeric', 'min:0'];

  protected $rules_items = [];
  protected $codigos = [];
  protected $newCodigos = [];
  public $allProducts;
  public function updateRules()
  {
    $this->rules_items['codigo'] = $this->getCodigoRule();
  }

  public function setCodigo($codigo)
  {
    $this->newCodigos[] = $codigo;
  }

  public function getRequiredString($aditional)
  {
    return 'required|in:' . $aditional;
  }

  public function getRules()
  {
    if ($this->rules_items) {
      $this->updateRules();
    }
    else {
      $this->generateRules();
    }
    
    return $this->rules_items;
  }

  public function getCodigoRule()
  {
    return [
      'required',
      Rule::in($this->codigos),
      Rule::notIn($this->newCodigos)
    ];
  }

  public function searchCodigos()
  {
    $this->allProducts = DB::connection('tenant')->table('productos')
    ->select(
      'ID',
      'ProCodi',
      'ProNomb',
      'ProPUCS',
      'prosto1 as stock_1',
      'prosto2 as stock_2',
      'prosto3 as stock_3',
      'prosto4 as stock_4',
      'prosto5 as stock_5',
      'prosto6 as stock_6',
      'prosto7 as stock_7',
      'prosto8 as stock_8',
      'prosto9 as stock_9',
      'unpcodi as unidad',
    )
    ->get();

    $this->codigos = $this->allProducts->pluck('ProCodi')->toArray();
  }

  public function generateRules()
  {
    $nv = self::NUMERIC_VALIDATION;
    $this->searchCodigos();
    $this->rules_items = [
      'codigo' => $this->getCodigoRule(),
      'stock' => $nv,
    ];
  }
}