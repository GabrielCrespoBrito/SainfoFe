<?php

namespace App\Util\Import\Excell\Producto;

use App\Grupo;
use App\Familia;

class CategoriaSupplier extends SupplierAbstract
{
  const NUMBER_INIT = 100;

  public function setInitData()
  {
    $this->entidadData = Grupo::with('fams')->get()->sortBy('grucodi');
  }

  public function getGrupoLastId()
  {
    $id = $this->entidadData->max('GruCodi');

    if (is_numeric($id)) {
      return math()->addCero($id +1, 2);
    }

    $last_id_numeric = -1;

    foreach ($this->entidadData as $grupo ) {

      $id = $grupo->GruCodi;

      if (!is_numeric($id)) {
        continue;
      }

      if ($id > $last_id_numeric) {
        $last_id_numeric = $id;
      }
    }

    if ($last_id_numeric === -1) {
      return '00';
    }

    return math()->increment($last_id_numeric + 1);
  }

  public function getNames()
  {
    $value = explode('>', $this->campoValue);

    return (object) [
      'grupo' => trim($value[0]),
      'familia' => isset($value[1]) ? trim($value[1]) : null
    ];
  }

  public function addToData($grupo)
  {
    $this->entidadData->push( $grupo );
  }

  public function createGrupo($grupoNombre, $familiaNombre = null )
  {
    $grupoCodi = $this->getGrupoLastId();

    $data_grupo = [
      'GruNomb' => $grupoNombre,
      'GruCodi' => $grupoCodi,
      'empcodi' => $this->empcodi,
    ];
    
    $grupo = Grupo::create($data_grupo);
    $familia = $grupo->createFamiliaDefault($familiaNombre);
    
    # Cargar al modelo las familias    
    $grupo->load('fams');

    $this->addToData($grupo);

    return [
      $grupo->GruCodi,
      $familia->famCodi
    ];
  }

  public function getOrCreateFamilia($grupo , $familia_name )
  {    
    if ($familia_name) {
      $familia = $grupo->fams->where('famNomb', $familia_name )->first();
      if (is_null($familia)) {
        $famcodi = $grupo->getFamiliaLastId();
        $familia = $grupo->createFamiliaDefault($familia_name, $famcodi );
        # Cargar al modelo las familias nuevas
        $grupo->load('fams');
      }
    }
    
    else {
      $familia = $grupo->fams->first();
    }

    return [ 
      $grupo->GruCodi,
      $familia->famCodi 
    ];
        
  }
  

  public function getOrCreate()
  {
    $names = $this->getNames();
    // |loremp-ipsum-odlor|loremp-ipsum-odlor|loremp-ipsum-odlor|loremp-ipsum-odlor|loremp-ipsum-odlor|loremp-ipsum-odlor|
    $grupo = $this->entidadData->where('GruNomb', $names->grupo)->first();

    if ($grupo) {
      return $this->getOrCreateFamilia($grupo, $names->familia );
    } 
    
    return $this->createGrupo($names->grupo, $names->familia);
  }

  public function handle(&$dataProcess)
  {
    $data = $this->getOrCreate();
    $dataProcess[$this->getHeader('grupo')] = $data[0];
    $dataProcess[$this->getHeader('familia')] = $data[1];
  }
}
