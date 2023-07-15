
<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones
  <span class="caret"></span></button>
  
  <ul class="dropdown-menu">
    <li><a href="{{ route('admin.usuario-empresa.create' , $usucodi ) }}"> <span class="fa fa-building"></span> Agregar Empresa </a></li>


    <li><a href="#" class="modificar-usuario"> <span class="fa fa-pencil"></span> Modificar </a></li>
    
    <li><a href="{{ route('admin.usuario-permisos.edit' , $usucodi ) }}"> <span class="fa fa-unlock-alt"></span> Permisos </a></li>

    {{-- <li><a href="{{ route('admin.usuarios.assign_role' , $usucodi ) }}"> Permisos </a></li> --}}

    <li><a href="#" onclick="event.preventDefault(); if(confirm('Esta seguro de cambiar el estado a este usuario')) document.getElementById('toggleActive{{ $usucodi }}').submit()">  
    <span class="fa {{ $active ? 'fa-ban' : 'fa-check' }}"></span>
    {{ $active ? "Desactivar" : "Activar" }} </a>
      <form method="post" id="toggleActive{{ $usucodi}}" action="{{ route('admin.usuarios.toggleActiveEstate' , $usucodi ) }}">@csrf</form>
    </li>

    {{-- Borrar --}}
    <li><a href="#" class="bg-red" onclick="event.preventDefault(); if(confirm('Esta seguro de cambiar el estado a este usuario')) document.getElementById('formDelete{{ $usucodi }}').submit()"> <span class="fa fa-trash"></span> Borrar </a>
      <form method="post" id="formDelete{{ $usucodi}}" action="{{ route('usuarios.borrar' , ['id_user' => $usucodi ]) }}">@csrf</form>
    </li>
    {{-- Borrar --}}


    <li class="divider"></li>    
  </ul>
</div>