<?php

namespace App\Policies;

use App\User;
use App\Venta;
use Illuminate\Auth\Access\HandlesAuthorization;

class VentasPolicy
{
  use HandlesAuthorization;

  public function view(User $user, Venta $venta)
  {
    return true;
  }


  public function edit(User $user, Venta $venta)
  {
    return false;
  }

  /**
   * Determine whether the user can create ventas.
   *
   * @param  \App\User  $user
   * @return mixed
   */
  public function create(User $user)
  {
    return false;
  }

  /**
   * Determine whether the user can update the venta.
   *
   * @param  \App\User  $user
   * @param  \App\Venta  $venta
   * @return mixed
   */
  public function update(User $user, Venta $venta)
  {
    //
  }

  /**
   * Determine whether the user can delete the venta.
   *
   * @param  \App\User  $user
   * @param  \App\Venta  $venta
   * @return mixed
   */
  public function delete(User $user, Venta $venta)
  {
    return false;
    return $venta->fe_rpta !== 9;
  }

  /**
   * Determine whether the user can restore the venta.
   *
   * @param  \App\User  $user
   * @param  \App\Venta  $venta
   * @return mixed
   */
  public function restore(User $user, Venta $venta)
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the venta.
   *
   * @param  \App\User  $user
   * @param  \App\Venta  $venta
   * @return mixed
   */
  public function forceDelete(User $user, Venta $venta)
  {
    //
  }
}
