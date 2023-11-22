<?php

namespace App\Models\Traits;

trait ImageUploadTrait
{
  public function uploadImage($file, $name)
  {
    $fh = FileHelper();
    $fh->only_nube = true;
    $logoName = $name . '.' . $file->extension();
    $fh->save_img($logoName, file_get_contents($file->getPathname()));
    return $logoName;
  }

  public function deleteImage()
  {
    $fh = fileHelper();
    $fh->only_nube = true;
    $fh->delete_img($this->getImage());
  }

  public function pathImage()
  {
    return $this->getPathImage($this->getImage());
  }

  public function getPathImage( $img_name )
  {
    return file_build_path(config('app.aws_url_bucket'), 'images', $img_name );
  }

}
