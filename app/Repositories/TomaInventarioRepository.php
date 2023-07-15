<?php

namespace App\Repositories;

use App\Models\TomaInventario\TomaInventario;
use Illuminate\Database\Eloquent\Model;

class TomaInventarioRepository extends RepositoryBase
{
  public function create(array  $data)
  {
    $user = auth()->user();
    $date_arr =  explode(' ', $data['InvFech'])[0];
    $date_split =  explode('-', $date_arr );
    $data['empcodi'] = empcodi();
    $data['panano'] = $date_split[0];
    $data['mescodi'] = $date_split[0] . $date_split[1];
    $data['user_Crea'] = $user->usulogi;
    $data['User_ECrea'] = gethostname();

    $ti = new TomaInventario();
    $ti->fill($data);
    $ti->save();

    return $ti;
  }

  public function update(array $data, $id)
  {
    // $model = $this->model->find($id);
    $toma_inventario  = $this->model->find($id);
    $user = auth()->user();
    $date_arr =  explode(' ', $data['InvFech'])[0];
    $date_split =  explode('-', $date_arr);
    $data['panano'] = $date_split[0];
    $data['mescodi'] = $date_split[0] . $date_split[1];
    $data['User_Modi'] = $user->usulogi;
    $data['User_EModi'] = gethostname();

    $toma_inventario->update($data);
    return $toma_inventario;
  }

}
