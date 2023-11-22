<?php

namespace App\Admin\Models;

use App\Admin\Models\Cliente\Cliente;
use App\Models\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Banner extends Model
{
  use 
  UsesSystemConnection,
  ImageUploadTrait;

  protected $table = "pagina_banners";

  public function getImageName()
  {
    return 'banners_pagina_' . time();
  }
  
  public function getImage()
  {
    return $this->imagen;
  }

  public static function allWithLogoPath()
  {
    $banners = Banner::all();
    $bannerData = [];
    foreach ($banners as $banner) {
      $data = $banner->toArray();
      $data['path'] = $banner->pathImage();
      $data['path_mobile'] = $banner->getPathImage($banner->imagen_mobile);
      $bannerData[] = $data;
    }
    return $bannerData;
  }
}
