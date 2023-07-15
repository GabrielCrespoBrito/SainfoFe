<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\Http\Requests\SubirCert;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\SubirCertificadoRequest;
use App\Events\Empresa\CertFacturacionInfoSave;
use App\Jobs\Empresa\UploadCertificate;

trait CertificateTrait
{
  public function subirCertificado($id)
  {
    $empresa = Empresa::findByCodi($id);
    return view('admin.empresa.subir_certificado', compact('empresa'));
  }

  public function storeCertificado(SubirCert $request, $id)
  {
    $empresa = Empresa::find($id);    
    
    (new UploadCertificate($request, $empresa->ruc()))->handle();

    // $fileHelper = FileHelper($empresa->EmpLin1);

    // if ($request->cert_key) {
    //   $fileHelper->save_cert( '.key', file_get_contents($request->cert_key->path()) );
    // }
    // if ($request->cert_cer) {
    //   $fileHelper->save_cert( '.cer', file_get_contents($request->cert_cer->path()) );
    // }
    // if ($request->cert_pfx) {
    //   $fileHelper->save_cert( '.pfx', file_get_contents($request->cert_pfx->path()) );
    // }

    noti()->success('Acción Exitosa', 'Se ha subido el archivo exitosamente');
    return redirect()->back();
  }



  public function checkCertificado(Request $request, $id)
  {
    $empresa = Empresa::find($id);
    $fileHelper = FileHelper($empresa->EmpLin1);

    $certs = [
      'key' => ['extention' => '.key', 'exists' => false],
      'cer' => ['extention' => '.cer', 'exists' => false],
      'pfx' => ['extention' => '.pfx', 'exists' => false],
    ];    

    // RadioComerCiales-

    foreach ($certs as $cert => &$data) {
      $data['exists'] = $fileHelper->certExist($data['extention']);
    }

    session()->flash('checkCertificado', $certs);
    return redirect()->back();
  }


  /**
   * Guardar información sobre el certificado y su clave sol
   * 
   * @return Json
   */
  public function certStore(SubirCertificadoRequest $request,  $empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    event(new CertFacturacionInfoSave($empresa, $request));
    $empresa->cleanCache();
    return response()->json(['message' => 'Información guardada exitosamente']);
  }


  public function configFinal()
  {
    return view('admin.empresa.config', ['empresa' => get_empresa()]);
  }

}
