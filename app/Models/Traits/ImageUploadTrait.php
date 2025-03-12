<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\File;

trait ImageUploadTrait
{

  public function getFolderPath( $imageName = null )
  {
    $folderPath = storage_path('app/public');

    return $imageName ? $folderPath . DIRECTORY_SEPARATOR . $imageName : $folderPath;
  }

  public function uploadImage($file, $name)
  {
    // $fh = FileHelper();
    // $fh->only_nube = true;
    $logoName = $name . '.' . $file->extension();
    // $fh->save_img($logoName, file_get_contents($file->getPathname()));
    // file_put_contents($file->getPathname());
    
    File::put(
      $this->getFolderPath($logoName), 
      file_get_contents($file->getPathname()) 
    );
    return $logoName;
  }

  public function deleteImage($imagen = null)
  {
    $imagen = $imagen ?? $this->getImage();

    $path = $this->getFolderPath($imagen);

    if (file_exists($path)) {
      unlink($path);
    }
  }

  public function pathImage()
  {
    return $this->getPathImage($this->getImage());
  }

  public function getPathImage( $img_name )
  {
    return asset( sprintf('storage/%s', $img_name ) );
  }

}
