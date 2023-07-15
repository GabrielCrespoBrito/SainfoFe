<?php

namespace App\Http\Controllers;

class ContactoController extends Controller
{
    public function showForm()
    {
        $htmlContacto = get_setting('html_contacto' , '-' );
        return view('contacto.medios', ['htmlContacto' => $htmlContacto]);
    }
}
