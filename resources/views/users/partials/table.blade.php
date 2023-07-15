@component('components.table' , ['thead' => ['Login', 'Nombre Usuario', 'Telefono', 'Estatus', ''] , 'url' => route('usuarios.search'),  'id' => "table-users" , 'class_name' => 'sainfo' ])
@slot('body')

  {{-- @dd("aqui estamos"); --}}

    @foreach( $users as $user )
    @if( auth()->user()->usucodi !== $user->usucodi  && !$user->isAdmin() )
    <tr class="" data-info="{{ $user }}" data-id="{{ $user->usucodi }}">
        <td> {{ $user->usulogi }} </td>  
        <td> {{ $user->usunomb }} </td>  
        <td> {{ $user->usutele }} </td>  
        <td>
          @if( $user->active )
            <span class="label label-success"> Activo </span>
          @else
            <span class="label label-default"> Inactivo </span>
          @endif
          </td>  
        <td>  
          @include('partials.column_accion', ['links' => [
            ['src' => route('usuarios.form',  $user->usucodi ), 'class' => 'modificar-usuario', 'texto' => 'Modificar'],
            ['src' => route('usuarios.cambiar-status', $user->usucodi ) ,  'class' => $user->active ? 'bg-red change-status' : 'bg-green 
            change-status'  , 'texto' =>  $user->active ? 'Desactivar' : 'Activar' ],
            ['src' => route('usuarios.asignar_permisos', $user->usucodi) , 'class' => '', 'texto' => 'Permisos'],
            ['src' => '#' , 'class' => 'eliminate-element', 'texto' => 'Eliminar', 'id' => $user->usucodi ],
          ]])
        </td>
      </tr>
      @endif
    @endforeach
  @endslot
@endcomponent


{{--@@@@
  ... eliminate-element ...
@@@@--}}