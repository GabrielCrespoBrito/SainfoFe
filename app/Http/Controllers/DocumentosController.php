<?php

namespace App\Http\Controllers;

use App\Mes;
use App\Venta;
use App\Empresa;
use App\Helpers\FHelper;
use App\Helpers\FtpHelper;
use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use App\Rules\ValidRecaptcha;
use Illuminate\Support\Facades\Storage;
use App\NotificacionDocumentosPendientes;
use App\Http\Requests\BusquedaDocumentoRequest;

class DocumentosController extends Controller
{
  public function index($tipo_documento, $lapso)
  {
    $documentos = NotificacionDocumentosPendientes::getEspecificTipo($tipo_documento, $lapso);

    return view('documentos.index', [
      'tipo_documento' => $tipo_documento,
      'lapso' => $lapso,
      'documentos' => $documentos,
    ]);
  }

  public function search(Request $request)
  {
    $busqueda = Venta::query()->with(['cliente_with' => function ($q) {
      $q->where('EmpCodi', empcodi())
        ->where('TipCodi', 'C');
    }, 'amazon' => function ($q) {
      $q->where('EmpCodi', empcodi());
    }]);


    $fileUpload = "<span class='btn btn-flat btn-xs btn-success'> Ok </span>";
    $fileNotUpload = "<span class='btn btn-flat btn-xs btn-danger'> No </span>";

    $busqueda
      ->where('EmpCodi', empcodi())
      ->where('fe_rpta', '!=', null)
      ->orderBy('VtaNumee', 'asc');

    if ($request->tipo != "todos") {
      $busqueda->where('TidCodi', '=', $request->tipo);
    }
    if ($request->mes != "todos") {
      $busqueda->where('MesCodi', '=', $request->mes);
    }

    if ($request->estatus != "todos") {

      if ($request->estatus == "1" || $request->estatus == "0") {
        $busqueda = $busqueda->get();
        $busqueda = $busqueda->where('amazon.Estatus', $request->estatus);
      } else {
        $busqueda = $busqueda->doesntHave('amazon');
      }
    }

    return datatables()
      ->of($busqueda)
      ->addColumn('xml', function ($venta) use ($fileUpload, $fileNotUpload) {
        if ($venta->amazon) {
          return $venta->amazon->XML ? $fileUpload : $fileNotUpload;
        }
        return $fileNotUpload;
      })
      ->addColumn('pdf', function ($venta) use ($fileUpload, $fileNotUpload) {
        if ($venta->amazon) {
          return $venta->amazon->PDF ? $fileUpload : $fileNotUpload;
        }
        return $fileNotUpload;
      })
      ->addColumn('cdr', function ($venta) use ($fileUpload, $fileNotUpload) {
        if ($venta->amazon) {
          return $venta->amazon->CDR ? $fileUpload : $fileNotUpload;
        }
        return $fileNotUpload;
      })
      ->addColumn('estado', function ($venta) use ($fileUpload, $fileNotUpload) {
        if ($venta->amazon) {
          if ($venta->amazon->Estatus) {
            return "<span class='btn btn-flat btn-xs btn-primary'> Todo subido </span>";
          } else {
            return "<span class='btn btn-flat btn-xs btn-warning'> Documentos Faltantes </span>";
          }
        } else {
          return "<span class='btn btn-flat btn-xs btn-danger'> Sin subir </span>";
        }
      })
      ->addColumn('acciones', 'documentos.partials.column_accion')
      ->rawColumns(['xml', 'pdf', 'cdr', 'estado', 'acciones'])
      ->toJson();
  }


  public function subirDocumentos()
  {
    $empresa = get_empresa();
    $meses = Mes::orderByDesc('mescodi')->get();
    return view('documentos.index_upload', compact('empresa', 'meses'));
  }

  public function downloadDocumentos(BusquedaDocumentoRequest $request)
  {
    $data = $request->all();

    $ruc = trim($request->ruc);

    $name =
      $ruc . '-' .
      $request->tipo_documento . '-' .
      strtoupper($request->serie) . '-' .
      agregar_ceros($request->numero, 6, 0);


    $nameCdr = 'R-' . $name . '.zip';
    $namePDF = $name . '.pdf';
    $nameEnvio = $name . '.zip';


    $empresa = Empresa::findByRuc($ruc);

    $fileHelper = new FHelper($ruc, optional($empresa)->codigo ?? '000');
    $filesToDownload = [];

    if ($fileHelper->existsInLocal(FHelper::CDR,  $nameCdr)) {
      $file = $fileHelper->getFilesInLocal(FHelper::CDR, $nameCdr);
      array_push($filesToDownload, ['name' => $nameCdr, 'content' => $file]);

      if ($fileHelper->existsInLocal(FHelper::PDF,  $namePDF)) {
        $file = $fileHelper->getFilesInLocal(FHelper::PDF, $namePDF);
        array_push($filesToDownload, ['name' => $namePDF, 'content' => $file]);
      }
      if ($fileHelper->existsInLocal(FHelper::ENVIO,  $nameEnvio)) {
        $file = $fileHelper->getFilesInLocal(FHelper::ENVIO, $nameEnvio);
        array_push($filesToDownload, ['name' => $nameEnvio, 'content' => $file]);
      }
    }

    if ($request->tipo_documento == "03" && $fileHelper->existsInLocal('pdf', $namePDF)) {
      $file = $fileHelper->getFilesInLocal(FHelper::PDF, $namePDF);
      array_push($filesToDownload, ['name' => $namePDF, 'content' => $file]);
    }

    if (count($filesToDownload)) {

      $zipper = new Zipper();

      $pathsFiles = [];

      foreach ($filesToDownload as $file) {

        $pathTemp = getTempPath($file['name']);
        $fileHelper->saveTemp($file['content'], $file['name']);
        chmod($pathTemp, 0755);
        array_push($pathsFiles, $pathTemp);
      }

      $nameCompress = str_random(5) . '__' . $nameEnvio;
      $pathCompress = getTempPath($nameCompress);

      $zipper
        ->make($pathCompress)
        ->add($pathsFiles)
        ->close();

      $headers = ['Content-type' => 'application/zip'];
      $response = \Response::download($pathCompress, NULL, $headers);
      ob_end_clean();
      return $response;
    } 
    
    else {
      notificacion("", "No se ha encontrado ningun documento con los datos suministrados", "error");
      return redirect()->back();
    }
  }

  public function _downloadDocumentos(BusquedaDocumentoRequest $request)
  {
    $ruc = trim($request->ruc);

    $name =
      $ruc . '-' .
      $request->tipo_documento . '-' .
      strtoupper($request->serie) . '-' .
      agregar_ceros($request->numero, 6, 0);

    $nameCdr = 'R-' . $name . '.zip';
    $namePDF = $name . '.pdf';
    $nameEnvio = $name . '.zip';

    $fileHelper =  FileHelper($ruc);
    $downloadFiles = [];

    $ftp = new FtpHelper('hola');


    $folder =  $ftp->existDirectory($ruc, false);

    if ($folder == false) {
      notificacion("", "No existe el Ruc consultado", "error");
      return redirect()->back();
    }

    $ftp->setFolder($folder);


    $pathCdr = $ftp->getPath(FtpHelper::FOLDER_CDR, $nameCdr);
    $pathEnvio = $ftp->getPath(FtpHelper::FOLDER_ENVIO, $nameEnvio);
    $pathPdf = $ftp->getPath(FtpHelper::FOLDER_PDF, $namePDF);

    
    if ($ftp->exists($pathCdr)) {
      array_push($downloadFiles, ['name' => $nameCdr, 'content' => $ftp->getFile($pathCdr)]);

      if ($ftp->exists($pathEnvio)) {
        array_push($downloadFiles, ['name' => $nameEnvio, 'content' => $ftp->getFile($pathEnvio)]);
      }

      if ($ftp->exists($pathPdf)) {
        array_push($downloadFiles, ['name' => $namePDF, 'content' => $ftp->getFile($pathPdf)]);
      }
    }

    if (count($downloadFiles) == 0) {
      notificacion("", "No se ha encontrado ningun documento con los datos suministrados", "error");
      return redirect()->back();
    }

    $zipper = new Zipper();
    $pathsFiles = [];

    foreach ($downloadFiles as $file) {

      $pathTemp = getTempPath($file['name']);
      $fileHelper->saveTemp($file['content'], $file['name']);
      chmod($pathTemp, 0755);
      array_push($pathsFiles, $pathTemp);
    }

    $nameCompress = date('Ymdhi') . '_' .  $nameEnvio;
    $pathCompress = getTempPath($nameCompress);

    $zipper
      ->make($pathCompress)
      ->add($pathsFiles)
      ->close();

    $response = \Response::download($pathCompress, NULL, ['Content-type' => 'application/zip']);
    ob_end_clean();
    return $response;
  }
  

  public function downloadSelected()
  {
  }


  public function downloadByGroup()
  {
  }


  public function uploadDocumento(Request $request)
  {
    $venta = Venta::find($request->id_documento);
    $this->uploadFile($request->id_documento, is_null($venta->amazon));
    return response()->json([true]);
  }


  public function uploadDocumentos(Request $request)
  {
    set_time_limit(3000);

    $empcodi = empcodi();

    $ventasNoUploads =
      \DB::connection('tenant')->table("ventas_cab")
      ->select('ventas_cab.VtaOper')
      ->whereNotIn('VtaOper', function ($query) {
        $query->select('VtaOper')->from('ventas_amazon');
      })
      ->where('ventas_cab.EmpCodi', '=', $empcodi)
      ->where('ventas_cab.fe_rpta', '=', 0)
      ->where('ventas_cab.MesCodi', '=', $request->mes);

    $ventasUploads =
      \DB::connection('tenant')->table('ventas_cab')
      ->join('ventas_amazon', 'ventas_amazon.VtaOper', '=', 'ventas_cab.VtaOper')
      ->where('ventas_amazon.Estatus', '=', 0)
      ->where('ventas_cab.EmpCodi', '=', $empcodi)
      ->where('ventas_cab.MesCodi', '=', $request->mes)
      ->select('ventas_cab.VtaOper');

    if ($request->tidcodi != "todos") {
      $ventasNoUploads->where('ventas_cab.TidCodi', $request->tidcodi);
      $ventasUploads->where('ventas_cab.TidCodi', $request->tidcodi);
    }

    $ventasNoUploads = $ventasNoUploads->get();
    $ventasUploads = $ventasUploads->get();

    foreach ($ventasNoUploads->chunk(50) as $ventas) {
      foreach ($ventas as $venta) {
        $this->uploadFile($venta->VtaOper);
      }
    }

    foreach ($ventasUploads->chunk(50) as $ventas) {
      foreach ($ventas as $venta) {
        $this->uploadFile($venta->VtaOper, false);
      }
    }

    return response()->json([true]);
  }

  public function uploadFile($id_venta, $is_new = true)
  {
    $fileHelper = fileHelper();
    $fileHelper->saveVenta($id_venta, $is_new);
  }
}
