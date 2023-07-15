<?php
namespace App\Util\Mike42\GfxPhp\Codec;

use App\Util\Mike42\GfxPhp\RasterImage;
// namespace Mike42\GfxPhp\Codec;

// use Mike42\GfxPhp\RasterImage;


interface ImageEncoder
{
    public function getEncodeFormats() : array;

    public function encode(RasterImage $image, string $format) : string;
}
