<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones
  <span class="caret"></span></button>
  
  <ul class="dropdown-menu">
    
    <li><a href="{{ route('admin.usuarios_documentos.edit' , [ 'id' => $ID ] ) }}"> Modificar </a></li>    
    <li><a href="#" class="bg-red" onclick="event.preventDefault(); if(confirm('Esta seguro de desea eliminar este documento')) document.getElementById('formDelete{{ $ID }}').submit()"> Eliminar </a>
      <form method="post" id="formDelete{{ $ID }}" action="{{ route('admin.usuarios_documentos.delete' , ['id' => $ID ]) }}">@csrf</form>
    </li>

  </ul>
</div>