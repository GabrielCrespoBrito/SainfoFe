<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChangePasswordRequest $request)
    {
        notificacion('Accion completada', 'Su contraseña ha sido actualizada', 'success');
        auth()->user()->update(['usucla2' => $request->input('password')]);
        return redirect()->route('home');
    }
}