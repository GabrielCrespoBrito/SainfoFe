<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MarcaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }


    public function model(array $row)
    {
	    return new Marca([
	       'MarCodi'     => $row[0],
	       'MarNomb'     => $row[1], 
	       'empcodi'     => empcodi(),
	    ]);
    }
}
