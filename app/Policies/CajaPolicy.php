<?php

namespace App\Policies;

use App\User;
use App\Caja;
use Illuminate\Auth\Access\HandlesAuthorization;

class CajaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the caja.
     *
     * @param  \App\User  $user
     * @param  \App\Caja  $caja
     * @return mixed
     */
    public function reaperturar(User $user, Caja $caja)
    {
        return $user->usucodi === $caja->UsuCodi;
    }

    public function cerrar(User $user, Caja $caja)
    {
        return $user->usucodi === $caja->UsuCodi;
    }    

    public function manipular(User $user, Caja $caja)
    {
        return $user->usucodi === $caja->UsuCodi;
    }

    public function eje_movimiento(User $user, Caja $caja)
    {
        // return false;
        return !$caja->isAperturada() && $user->usucodi === $caja->UsuCodi;
    }    

}
