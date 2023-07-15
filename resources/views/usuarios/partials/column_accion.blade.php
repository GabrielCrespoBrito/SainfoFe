
<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones
  <span class="caret"></span></button>
  
  <ul class="dropdown-menu">
    <li><a href="{{ route('usuarios.empresa.create' , [ 'id_user' => $usucodi] ) }}"> Agregar Empresa </a></li>

    <li><a href="#" class="modificar-usuario"> Modificar </a></li>    
    <li><a href="{{ route('usuarios.assign_role' , $usucodi ) }}"> Permisos </a></li>  

    {{-- Borrar --}}
        <li><a href="#" class="bg-red" onclick="event.preventDefault(); if(confirm('Esta seguro de cambiar el estado a este usuario')) document.getElementById('formDelete{{ $usucodi }}').submit()"> Borrar </a>
      <form method="post" id="formDelete{{ $usucodi}}" action="{{ route('usuarios.borrar' , ['id_user' => $usucodi ]) }}">@csrf</form>
    </li>
    {{-- Borrar --}}


    <li><a href="#" class="{{ $active ? 'bg-red' : 'bg-green' }}" onclick="event.preventDefault(); if(confirm('Esta seguro de cambiar el estado a este usuario')) document.getElementById('toggleActive{{ $usucodi }}').submit()"> {{ $active ? "Desactivar" : "Activar" }} </a>
      <form method="post" id="toggleActive{{ $usucodi}}" action="{{ route('usuarios.toggleActiveEstate' , ['id_user' => $usucodi ]) }}">@csrf</form>
    </li>


    <li class="divider"></li>    
  </ul>
</div>