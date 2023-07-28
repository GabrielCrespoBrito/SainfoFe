<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use App\EmpresaOpcion;

class SaveWeb
{
  public $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function handle()
  {
    $empresa = new Empresa;
    $empresa->empcodi = agregar_ceros(Empresa::max('empcodi'), 3);
    $empresa->EmpNomb = $this->data['nombre_empresa'];
    $empresa->EmpLin1 = $this->data['ruc'];
    $empresa->EmpLin2 = $this->data['direccion'];
    $empresa->EmpLin3 = $this->data['email'];
    $empresa->EmpLin4 = $this->data['telefonos'];
    $empresa->tipo = 'escritorio';
    //  $this->data['tipo'];
    $empresa->venc_certificado = $this->data['venc_certificado'];
    $empresa->end_plan = $this->data['fecha_suscripcion'];
    $empresa->save();
    
    // venc_certificado
    EmpresaOpcion::createDefault($empresa->empcodi);

    // $empresa->updateDataAdicional(['tipo' => $this->data['tipo'], 'venc_certificado' => $this->data['venc_certificado']]);
  }

  public function saveData()
  {
  }
}
