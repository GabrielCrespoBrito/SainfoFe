<div class="filtros">

  <a class="btn btn-flat btn-default ultra btn-succes {{ $unread  ? '' : 'active' }}" href="{{ route('admin.notificaciones.index', ['type' => 'read']) }}">

  <span class="fa fa-eye"></span> Leida </a>

  <a class="btn btn-flat btn-default ultra {{ $unread ? 'active' : '' }}" href="{{ route('admin.notificaciones.index', ['type' => 'unread']) }}">

  <span class="fa fa-bell-o"></span> Pendientes </a>

</div>

<hr>



