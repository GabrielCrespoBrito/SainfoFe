<?php

namespace App\Http\Controllers\UserLocal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserLocal\UserLocal;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\UserLocal\UserLocalStoreRequest;
use App\Http\Requests\UserLocal\UserLocalUpdateRequest;

class UserLocalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $userlocal;

    public function __construct()
    {
        $this->userlocal = new UserLocal();
    }

    public function index()
    {
        $userslocal = UserLocal::with('user')->get();

        return view('user_local.index', ['userslocal' => $userslocal]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = get_empresa();
        $users = $empresa->users;
        $locales = $empresa->locales;
        $userlocal = $this->userlocal;
        return view('user_local.create', compact('users','locales','userlocal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( UserLocalStoreRequest $request)
    {
        $data = $request->all();
        $data['numcodi'] = "000000";
        $data['sercodi'] = "0000";
        $data['empcodi'] = empcodi();
        $data['defecto'] = 0;

        $userlocal = UserLocal::create($data);

        notificacion( '' , 'Usuario Local Creado' );
        return redirect()->route('user-local.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($usucodi,$loccodi)
    {
        $empresa = get_empresa();

        $users = $empresa->users;
        $locales = $empresa->locales;
        $userlocal = $this->userlocal->find( $usucodi, $loccodi );

        return view('user_local.edit', compact('users','locales','userlocal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserLocalStoreRequest $request, $usucodi, $loccodi)
    {
        $userlocal = $this->userlocal->find( $usucodi, $loccodi );
        $previousDefecto = $userlocal->defecto;
        
        $data = [];
        $data['loccodi'] = $request->loccodi;
        
        try {
            DB::table('usuario_local')
            ->where('loccodi', $userlocal->loccodi)
            ->where('empcodi', $userlocal->empcodi )
            ->where('usucodi', $userlocal->usucodi)
            ->update($data);                        
        } catch ( QueryException $e) {
            return back()->withErrors(['Usuario ya esta asociado ha este local' , $e->getMessage()]);
        }
        
        notificacion( '' , 'Usuario local modificado exitosamente' );
        return redirect()->route('user-local.index');
    }

    /**
     * Poner local por defecto a un usuario especifico.
     * 
     */
    public function setDefaultLocal( $usucodi, $loccodi )
    {
        resolve(UserLocal::class)
        ->cleanDefault($usucodi)
        ->setDefault($usucodi,$loccodi);

        notificacion('', 'Local por defecto asignado satisfactoriamente');
        return back();
    }

    public function consultLocals()
    {
      $user_locales = auth()->user()->locales->load('local');
      $locals = [];
      
      foreach( $user_locales as $user_local )
      {
        $locals[] = [
          'url' =>  route('user-local.default', ['usucodi' => $user_local->usucodi, 'loccodi' => $user_local->loccodi ]),
          'descripcion' => optional($user_local->local)->LocNomb,
          'id' => optional($user_local->local)->LocCodi,
          'selected' => $user_local->defecto
        ];
      }

      return  response()->json(['locals' => $locals ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        list( $usucodi , $local ) = explode('-', $id );

        $empcodi = empcodi();

        DB::table('usuario_local')
            ->where('loccodi', $local )
            ->where('empcodi', $empcodi )
            ->where('usucodi', $usucodi )
            ->delete();

        DB::table('usuario_documento')
        ->where('loccodi', $local)
          ->where('empcodi', $empcodi)
          ->where('usucodi', $usucodi)
          ->delete();

        notificacion('', 'Registro eliminado exitosamente');
        return back();
    }
}
