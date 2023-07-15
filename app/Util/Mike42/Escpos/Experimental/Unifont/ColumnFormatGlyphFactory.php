<?php
namespace App\Util\Mike42\Escpos\Experimental\Unifont;

// namespace Mike42\Escpos\Experimental\Unifont;

interface ColumnFormatGlyphFactory
{
    public function getGlyph($codePoint);
}
