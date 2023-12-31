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
    $empresa->EmpLin5 = $this->data['nombre_empresa'];
    $empresa->tipo = 'escritorio';
    //  $this->data['tipo'];
    $empresa->emis_certificado = $this->data['emis_certificado'];
    $empresa->venc_certificado = $this->data['venc_certificado'];

    if (isset($this->data['ubigeo'])) {
      $empresa->setUbigeo($this->data['ubigeo']);
    }
    $empresa->save();
    // venc_certificado
    EmpresaOpcion::createDefault($empresa->empcodi);
    $empresa->createPlanes(true);


    // _dd("creado");
    // exit();

    // $empresa->updateDataAdicional(['tipo' => $this->data['tipo'], 'venc_certificado' => $this->data['venc_certificado']]);

    return $empresa;
  }

  public function saveData()
  {
  }
}
