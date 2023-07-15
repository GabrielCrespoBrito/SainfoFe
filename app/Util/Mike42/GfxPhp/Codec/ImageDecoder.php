<?php
namespace App\Util\Mike42\GfxPhp\Codec;

use App\Util\Mike42\GfxPhp\RasterImage;

// use Mike42\GfxPhp\RasterImage;

interface ImageDecoder
{
    public function getDecodeFormats() : array;

    public function identify(string $blob) : string;

    public function decode(string $blob) : RasterImage;
}
