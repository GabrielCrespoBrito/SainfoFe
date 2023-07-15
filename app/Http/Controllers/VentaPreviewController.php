<?php

namespace App\Http\Controllers;

use App\Jobs\Venta\GeneratePrevPDF;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FacturaSaveRequest;

class VentaPreviewController extends Controller
{
  public function create(FacturaSaveRequest $request)
  {
    $previewPath = (new GeneratePrevPDF($request))->handle();
    $realPath = file_build_path($previewPath);
    $pathJs = str_replace('\\', '/', $previewPath);

    // _dd([
    //   'path' => asset($previewPath),
    //   'path_' => str_replace('\\', '/', asset($previewPath)),
    //   'pathReal' => $realPath,
    //   'pathJs' => $pathJs
    // ]);
    // exit();

    return response()->json([
      'path' => asset($previewPath),
      'path_' => str_replace('\\', '/', asset($previewPath)),
      'pathReal' => $realPath,
      'pathJs' => $pathJs 
    ]);
  }
}
