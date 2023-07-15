<div class="row">

  <div class="col-md-12 acciones-div ww text-right">
    <a href="{{ route('home') }}" class="btn btn-danger btn-flat pull-right"> Salir </a>
    <a href="#" target="_blank" data-url="{{ route('reportes.kardex_valorizado_pdf', ['mes' => 'mes', 'local' => 'local_' , 'tipo' => 'tipo', 'reprocesar' => 'reprocesar' , 'formato' => 'formato' ]) }}" class="btn btn-primary btn-flat pull-left generateReport"> <span class="fa fa-doc"></span> Buscar </a>
  </div>

</div>
