<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
  public function __construct(User $user)
  {
    $this->model = $user;
  }

  public function all()
  {
  }

  public function create(array $data)
  {
    $userData['usucodi'] = User::ultimoCodigo();
    $userData['usulogi'] = $data['usuario'];
    $userData['usunomb'] = $data['nombre'];
    $userData['carcodi'] = User::TIPO_ASISTENTE;
    $userData['usucla2'] = $data['password'];
    $userData['usutele'] = $data['telefono'];
    $userData['usudire'] = $data['direccion'];
    $userData['email']   = $data['email'];
    if( isset($data['verificate']) ){
      $userData['verificate'] = $data['verificate'];
    }

    $userData['active']  = "1";
    $userData['UDelete'] = "";
    return $this->model->create($userData);
  }

  public function update(array $data, $id)
  {
  }

  public function delete($id)
  {
  }

  public function find($id)
  {
  }
}
