{{--
<p>
	<a href="{{ route('admin.comandos', 'instalacion') }}" class="btn btn-primary"> Instalación </a>
Establecer todos los archivos de configuración
</p> --}}

{{-- <p>
	<a href="{{ route('admin.comandos', 'verificardatabase') }}" class="btn btn-primary"> Verificar base de datos </a>
Agregar todos los campos y tablas en la base de datos que hagan falta
</p> --}}

<p>
  <a href="{{ route('admin.comandos', 'parametros') }}" class="btn btn-primary"> Valores de Configuraciones </a>
  Agregar nuevos parametros de configuración si existen
</p>

<p>
  <a href="{{ route('admin.comandos', 'eliminar_temporales') }}" class="btn btn-primary"> Archivos temporales</a>
  Eliminar los archivos temporales de la carpeta temp
</p>

<p>
  <a href="{{ route('admin.comandos', 'barradebug') }}" class="btn btn-primary"> Quitar barra de debug</a>
</p>

<p>
  <a href="{{ route('admin.comandos', 'permisos') }}" class="btn btn-primary"> Registrar Nuevos Permisos Solo a Usuario Principal</a> 
  <a href="{{ route('admin.comandos', 'permisos_all') }}" class="btn btn-primary"> Registrar Nuevos Permisos A todos los Usuarios</a> 
  Registros Permisos Recientes que se halla agregado y asignarselo a usuarios 
</p>

<p>
  <a href="{{ route('admin.comandos', 'medios_pagos') }}" class="btn btn-primary"> Sincronizar Medios de Pago  </a> Registros A Empresas las Nuevas formas de pagos registradas en el sistema
</p>

<p>
  <a href="{{ route('admin.comandos', 'limpiar_cache') }}" class="btn btn-primary"> Limpiar Cache </a>
</p>


<form action="{{ route('admin.save_img_footer_banner') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="row">

    <div class="col-md-3">
      @php
      $dimensiones = config('app.logos_dimenciones.footer');
      @endphp

      <button class="btn btn-primary " name="enviar" type="submit">
        Guardar Nuevo Banner Sainfo ({{$dimensiones['width']}}x{{$dimensiones['height']}}) 
      </button>
    </div>

    <div class="col-md-9">
      <input class="form-control input-sm {{ $errors->has('img_footer') ? 'has-error' : '' }}" require="required" name="img_footer" type="file">
    </div>

  </div>

</form>
</p>




<p>
<form action="{{ route('admin.config.exeCode') }}" method="post">
  {{ csrf_field() }}

  <div class="row">

    <div class="col-md-3">
      <button class="btn btn-primary " name="enviar" type="submit"> Ejecutar Codigo </button>
    </div>

    <div class="col-md-9">
      <input class="form-control input-sm {{ $errors->has('codigo') ? 'has-error' : '' }}" require="required" name="codigo" type="text">
    </div>

  </div>

</form>
</p>