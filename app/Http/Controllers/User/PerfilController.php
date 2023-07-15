<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PerfilRequest;

class PerfilController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth()->user();
        return view('usuarios.perfil' , ['user' => $user] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PerfilRequest $request)
    {
        notificacion('Accion completada', 'Se ha actualizado su perfil correctamente', 'success');
        auth()->user()->update( $request->only('usunomb','usutele','usudire','email') );
        return redirect()->route('home');
    
        
    }
}
