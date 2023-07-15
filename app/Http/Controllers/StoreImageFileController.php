<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreImageFileController extends Controller
{
  public function createFile(Request $request)
  {
    //generate random file name
    $randFileName = 'MyFile' . uniqid();
    //save image file on root website folder
    file_put_contents($randFileName, base64_decode($request->input('base64ImageContent')));
    //return file name back to client
    return response($randFileName)
      ->header('Content-Type', 'text/plain');
  } 
}
