<div class="row  filters-row">
<div class="col-md-10 ">
  <div class="row">
    
    {{-- Filtro por estado --}}
    <div class="col-md-3">
    {!! Form::select( 'status' , array_unshift_assoc($status, "todos", '-- Todos --') ,	$status_nuevo,	[ 'class' => "form-control input-sm", 'data-reloadtable' => 'table' ])     
    !!}
    </div>

  </div>
</div>


  <div class="col-md-2">
    {{-- Recargar la tabla --}}
    <button class="btn btn-primary pull-right btn-flat" data-reloadtable="table"> <span class='fa fa-refresh'></span> Recargar </button>  
  </div>

</div>