@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Empresas',
'titulo_pagina' => 'Empresas',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/empresa/index.js' ]]
])

@push('js')
<script>
  var url_consulta = "{{ route('admin.empresa.search') }}";
</script>

@include('partials.errores')
@endpush

@slot('contenido')

<form id="eliminar-user" action="#" style="display: none">
  @csrf
  <input name="codigo">
</form>

<div class="row">
  
  <div class="col-md-3">
    <div class="form-group">
      <select name="status" class="form-control">
        <option value="1" selected> Activas </option>
        <option value="0"> Inactivas </option>
      </select>
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <select name="venc_certificado" class="form-control">
        <option value="" selected> -- Todos -- </option>
        <option value="activas"> Activas </option>
        <option value="por_vencer"> Por Vencer </option>
        <option value="vencidas"> Vencidas </option>
      </select>
    </div>
  </div>


    <div class="col-md-3">
    <div class="form-group">
      <select name="tipo" class="form-control">
        <option value="" selected> -- Todos -- </option>
        <option value="web"> Web </option>
        <option value="escritorio"> Escritorio </option>
      </select>
    </div>
  </div>



  <div class="col-md-3">
    <div class="acciones-div">
      <a href="{{ route('admin.empresa.create') }}" class="btn btn-primary btn-flat pull-right crear-nuevo-usuario"> <span class="fa fa-plus"></span> Nuevo </a>
    </div>
  </div>

</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
<table class="table table-bordered table-hover sainfo-table user-table" id="datatable">
  <thead>
    <tr>
      <td> Código </td>
      <td> Nombre </td>
      <td> RUC </td>
      <td> Estado </td>
      <td> Ambiente </td>
      <td> Documentos </td>
      <td> Tipo </td>
      <td> Fech. Cert </td>
      <td> Suscripción </td>
      <td> Acciones </td>
    </tr>
  </thead>
</table>
</div>

@include('usuarios.partials.modal_usuario')
@include('usuarios.partials.modal_eliminar_usuario')
@include('admin.empresa.partials.modal_delete')



@endslot
@endview_data