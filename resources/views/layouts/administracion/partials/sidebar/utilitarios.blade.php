  <li class="treeview">

    <a href="#">
      <i class="fa fa-building-o"></i> <span>Utilitario</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>

    <ul class="treeview-menu">

      <li>
        <a target="_blank" href="{{ route('empresa.parametros', empcodi()) }}"><i class="fa fa-circle-o"></i> Parametros </a>
      </li>

      <li>
        <a target="_blank" href="{{ route('locales.index') }}"><i class="fa fa-circle-o"></i> Locales </a>
      </li>

      <li>
        <a target="_blank" href="{{ route('formas-pago.index') }}"><i class="fa fa-circle-o"></i> Forma de Pagos </a>
      </li>

      <li>
        <a target="_blank" href="{{ route('medios_pagos.index') }}"><i class="fa fa-circle-o"></i> Medios de Pagos </a>
      </li>


      <li>
        <a target="_blank" href="{{ route('usuarios.index') }}"><i class="fa fa-circle-o"></i> Usuarios </a>
      </li>

      <!-- 

    <li>
      {{-- <a href="#modalTC" id="tc-modal" data-toggle="modal"><i class="fa fa-building-o"></i> Tipo Cambio </a> --}}
      <a href="#" data-url= "{{ route('tipo_cambio.current') }}" id="tc-modal"><i class="fa fa-building-o"></i> Tipo Cambio </a>
    </li> -->

      @can('administracion')
      <li>
        <a target="_blank" href="{{ route('monitoreo.empresas.index') }}">
          <i class="fa fa-circle-o"></i> Monitoreo Docs </a>
      </li>
      @endcan

      <li>
        <a target="_blank" href="{{ route('vendedor.index') }}"><i class="fa fa-users"></i> Vendedores </a>
      </li>



      {{-- Empresa de transporte --}}
      <li>
        <a target="_blank" href="{{ route('empresa_transporte.index') }}"><i class="fa fa-circle-o"></i> Empresa transporte </a>
      </li>

      {{-- Transportista --}}
      <li>
        <a target="_blank" href="{{ route('transportista.index') }}"><i class="fa fa-circle-o"></i> Transportistas </a>
      </li>

      {{-- Vehiculos --}}
      <li>
        <a target="_blank" href="{{ route('vehiculo.index') }}"><i class="fa fa-circle-o"></i> Vehiculos </a>
      </li>

      <li>
        <a target="_blank" href="{{ route('cierre.index') }}"><i class="fa fa-circle-o"></i> Cierres </a>
      </li>

      <li><a href="{{ route('importar.productos.create') }}"><i class="fa fa-circle-o"></i> Importar Productos </a></li>
      <li><a href="{{ route('importar.clientes.create') }}"><i class="fa fa-circle-o"></i> Importar Clientes </a></li>
      <li><a href="{{ route('importar.ventas.create') }}"><i class="fa fa-circle-o"></i> importar Ventas </a></li>

      <li><a href="{{ route('export.excell') }}"><i class="fa fa-circle-o"></i> Exportar</a></li>


    </ul>

  </li>