<?php

namespace App\Http\Controllers\ModuloApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuloApi\UserRegularStoreRequest;
use App\ModuloApi\Models\User\User;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegularStoreRequest $request)
    {
        $data = $request->all();
        $user = User::create($data);
        return response()->json(['user' => $user], 200);
    }
}