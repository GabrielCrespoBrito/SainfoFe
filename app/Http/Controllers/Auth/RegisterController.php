<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = '/verificar';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware(['guest', 'registration_user.active:1']);
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make(
      $data,
      [
        'name' => 'required|string|max:255|alpha_num|unique:usuarios,usulogi',
        'email' => 'required|string|email|max:255|unique:usuarios,email',
        'password' => 'required|string|alpha_num|min:8|confirmed',
        'plan_id' => 'sometimes|nullable|exists:suscripcion_system_planes,id',
        'aceptar_politica' => 'required',
      ],
      ['name.unique' => 'El nombre de usuario ya esta tomado']
    );
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\User
   */
  protected function create(array $data)
  {
    return User::create([
      'usucodi' => User::ultimoCodigo(),
      'usulogi' => strtoupper($data['name']),
      'email' => $data['email'],
      'usucla2' => $data['password'],
      'ususerf' => $data['plan_id'] ?? null,
      'carcodi' => User::TIPO_DUENO,
      'active' => 0
    ]);
  }

  protected function registered(Request $request, $user)
  {
  }
}
