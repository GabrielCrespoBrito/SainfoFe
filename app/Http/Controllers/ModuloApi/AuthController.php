<?php

namespace App\Http\Controllers\ModuloApi;

use Illuminate\Http\Request;
use App\ModuloApi\Models\User\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username )
        ->where('password', $request->password )
        ->first();



        if( $user ){
            return response()->json(['login' => true , 'token' => $user->getToken() ], 200);
        }

        return response()->json(['login' => false, 'error' => 'Nombre de usuario o contraseña incorrectos' ], 400);
    }
}