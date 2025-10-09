<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use App\TipoDocumento;
use App\ClienteProveedor;
use App\Util\ResultTrait;



class ClienteUpdateInfo
{
  use ResultTrait;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  protected $empresa;
  protected $tipo_documento;
  protected $tipo_informacion;

  public function __construct(Empresa $empresa, $tipo_documento, $tipo_informacion)
  {
    $this->empresa = $empresa;
    $this->tipo_documento = $tipo_documento;
    $this->tipo_informacion = $tipo_informacion;
  }

  public function getClientes()
  {
    $query = ClienteProveedor::where('TipCodi', ClienteProveedor::TIPO_CLIENTE);

    if( $this->tipo_documento == 'all' ) {
      return $query->whereIn('TDocCodi', [TipoDocumento::DNI, TipoDocumento::RUC])
      ->get()
      ->chunk(50);
    }

    if( $this->tipo_documento == '6' ) {
      return $query->where('TDocCodi', TipoDocumento::RUC)->get()->chunk(50);
    }

    if( $this->tipo_documento == '6-20' ) {
      return $query->where('TDocCodi', TipoDocumento::RUC)->where('PCRucc', 'like', '20%')->get()->chunk(50);
    }

    if( $this->tipo_documento == TipoDocumento::DNI ) {
      return $query->where('TDocCodi', TipoDocumento::DNI)
      ->get()
      ->chunk(50);
    }



  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    empresa_bd_tenant($this->empresa->empcodi);
    
    $clientes = $this->getClientes();

    dd($clientes);

    foreach($clientes as $chunk) {
      foreach($chunk as $cliente) {

      if( $this->tipo_informacion == 'retencion' ) {
        $cliente->updateAgenteRetencion();
      }

      if( $this->tipo_informacion == 'informacion' ) {
        $cliente->updateInformacion();
      }

      if( $this->tipo_informacion == 'all' ) {
          $cliente->updateInformacion(true);
        }
      }
    }
  }
}
