<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use App\Http\Requests\Empresa\EmpresaUpdateVisualizacionRequest;

trait VisualTrait
{
  public function updateVisual(EmpresaUpdateVisualizacionRequest  $request, $id)
  {
    $empresa = Empresa::find($id);
    $data = $request->only('logo_principal', 'logo_secundario', 'logo_subtitulo', 'logo_marca_agua', 'img_footer');

    if (isset($data['logo_principal'])) {
      $img = \Image::make($data['logo_principal']);
      $img->encode('jpeg');
      $empresa->EmpLogo = $img;
    }

    if (isset($data['logo_secundario'])) {
      $img = \Image::make($data['logo_secundario']);
      $img->encode('jpeg');
      $empresa->EmpLogo1 = $img;
    }

    if (isset($data['logo_subtitulo'])) {
      $img = \Image::make($data['logo_subtitulo']);
      $img->encode('jpeg');
      $empresa->EmpDWeb = $img;
    }

    if (isset($data['logo_marca_agua'])) {
      $img = \Image::make($data['logo_marca_agua']);
      $img->encode('jpeg');
      $empresa->FE_RESO = $img;
    }

    if (isset($data['img_footer'])) {
      $fh = FileHelper();
      $fh->only_nube = true;
      $img_name = $empresa->getUUid() . '_pie_pdf' .  '.png';
      $fh->save_img($img_name, file_get_contents($data['img_footer']->getPathname()));
      $link = file_build_path(config('app.aws_url_bucket'), 'images', $img_name);
      $empresa->updateDataAdicional(['footer_img' => $link]);
    }

    $empresa->save();
    $empresa->cleanCache();
    noti()->success('Acción exitosa', 'Se ha modificado exitosamente la información de la empresa');
    return back();
  }

}
