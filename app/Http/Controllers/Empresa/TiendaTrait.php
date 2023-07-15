<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\SubirCertificadoRequest;
use App\Events\Empresa\CertFacturacionInfoSave;
use App\Http\Requests\CredencialesTiendaRequest;
use App\Jobs\Empresa\UploadCertificate;

trait TiendaTrait
{
  public function storeCredencialesTienda(CredencialesTiendaRequest $request, $id)
  {
    $empresa = Empresa::find($id);
    
    $empresa->updateDataAdicional($request->only('woocomerce_api_url', 'woocomerce_client','woocomerce_client_key'));

    noti()->success('Acción Exitosa', 'Se han guardado exitosamente las credenciales de la tienda');
    return redirect()->back();
  }
}
