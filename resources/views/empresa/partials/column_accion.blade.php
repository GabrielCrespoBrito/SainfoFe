<div class="dropdown">
  <button 
    class="btn btn-xs btn-default dropdown-toggle" 
    type="button" 
    data-toggle="dropdown"> Acciones <span class="caret"></span>
  </button>
  
  <ul class="dropdown-menu sainfo">
    <li><a href="{{ route('usuarios.empresa.create' , ['id_user' => "all"  , 'id_empresa' => $empcodi ] ) }}"> Agregar Usuario </a></li>
    <li><a href="{{ route('usuarios_documentos.create' , $empcodi) }}">  Agregar documento </a></li>
    <li><a href="{{ route('empresa.subirCertificado' , $empcodi) }}">Subir certificado </a></li>    
    <li><a href="{{ route('empresa.usos.update' , $empcodi) }}">Actualizar Consumo </a></li>
    <li><a class="delete-empresa" style="background-color:red;color:white;" data-toggle="modal" data-target="#modalDeleteEmpresa"> Eliminar Empresa </a></li>
  </ul>
</div>

