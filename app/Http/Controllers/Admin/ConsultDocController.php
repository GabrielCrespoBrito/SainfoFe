<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SunatHelper;
use App\Util\Sunat\Request\credentials\CredentialDatabase;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusResolver;

class ConsultDocController extends Controller
{
  // Verificar Documentos
  public function index()
  {
    $empresas = Empresa::formatList(true);
    return view('admin.consult_doc.index', compact('empresas'));
  }

  public function docConsult(Request $request)
  {
    ob_end_clean();

    try {

      // CDR
      if( true ){
        $empresa = $this->empresa;

        $resolverConsult =
          new ConsultStatusResolver(
            $empresa->getProveedor(),
            new CredentialDatabase($empresa),
            $empresa->isProduction(),
            $cdr
          );
        return $resolverConsult->getCommunicator();
      }

      else {

        
        $data = [
        'ruc' => $request->ruc_empresa,
        'usuario_sol' => $request->usuario_sol,
        'clave_sol' => $request->clave_sol,
        'ticket' => $request->ticket,
      ];
      
      
      $sent = SunatHelper::ticket($request->ticket, "", true, $data);
      
      if (isset($sent->status->content)) {
        $nameFile = time() . ".zip";
        $tempPath = getTempPath($nameFile, $sent->status->content);
        return response()->download($tempPath, $nameFile);
      }
      
      else {
        if ($sent->statusCdr) {
          $message = $sent->statusCdr->statusCode . ' | ' . $sent->statusCdr->statusMessage;
          noti()->error($message);
          return redirect()->back();
        } else {
        }
      }
    }
    } catch (\Throwable $th) {
      noti()->error( "Error: " .  $th->getMessage());
      return redirect()->back();
    }
  }

  public function resConsult(Request $request)
  {
    ob_end_clean();

    try {

      $data = [
        'ruc' => $request->ruc_empresa,
        'usuario_sol' => $request->usuario_sol,
        'clave_sol' => $request->clave_sol,
        'ticket' => $request->ticket,
      ];


      $sent = SunatHelper::ticket($request->ticket, "", true, $data);

      if (isset($sent->status->content)) {
        $nameFile = time() . ".zip";
        $tempPath = getTempPath($nameFile, $sent->status->content);
        return response()->download($tempPath, $nameFile);
      }
      else {
        if ($sent->statusCdr) {
          $message = $sent->statusCdr->statusCode . ' | ' . $sent->statusCdr->statusMessage;
          noti()->error($message);
          return redirect()->back();
        } else {
        }
      }
    } catch (\Throwable $th) {
      noti()->error("Error: " .  $th->getMessage());
      return redirect()->back();
    }
  }

}
