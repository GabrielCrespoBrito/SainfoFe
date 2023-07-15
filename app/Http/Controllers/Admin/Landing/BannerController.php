<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Admin\Models\Banner;
use App\Admin\Models\Testimonio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\BannerRequest;

class BannerController extends Controller
{
  public function index()
  {
    return view('admin.pagina.banners.index', ['banners' => Banner::all()]);
  }
  public function create()
  {
    $banner = new Banner();
    return view('admin.pagina.banners.create', compact('banner'));
  }

  public function edit($id)
  {
    $banner = Banner::find($id);
    return view('admin.pagina.banners.edit', compact('banner'));
  }


  public function store(BannerRequest  $request)
  {
    DB::beginTransaction();
    try {
      $banner = new Banner();
      $banner->nombre = $request->nombre;
      $banner->imagen = $banner->uploadImage($request->file('imagen'), $banner->getImageName());
      $banner->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => $th->getMessage()
      ], 400);
    }

    noti()->success('Banner Guardado Exitosamente');
    return redirect()->route('admin.pagina.banners.index');
  }

  public function update(BannerRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $banner = Banner::find($id);
      $banner->nombre = $request->nombre;
      $file = $request->file('imagen');
      if ($file) {
        $banner->deleteImage();
        $banner->imagen = $banner->uploadImage($file, $banner->getImageName());
      }
      $banner->save();
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['message' => $th->getMessage()], 400);
    }

    noti()->success('Banner Actualizado Exitosamente');
    return redirect()->route('admin.pagina.banners.index');
  }


  public function delete($id)
  {
    $banner = Banner::findOrfail($id);
    $banner->deleteImage();
    $banner->delete();
    noti()->success('Banner Eliminado exitosamente');
    return back();
  }
}
