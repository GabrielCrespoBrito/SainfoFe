<?php

namespace App\Policies;

use App\User;
use App\Compra;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompraPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index( User $user , Compra $compra )
    {
        // return $user->can('index compras');
    }

    public function create(User $user, Compra $compra)
    {
        // return $user->can('create compras');
    }

    public function edit(User $user, Compra $compra)
    {
        // if( $user->can('edit compras') ){
            // return $compra->empresa_id == empcodi();
        // }

        // return false;
    }

    public function delete(User $user, Compra $compra)
    {
        // if ($user->can('delete compras')) {
            // return $compra->empresa_id == empcodi();
        // }
// 
        // return false;
    } 



}
