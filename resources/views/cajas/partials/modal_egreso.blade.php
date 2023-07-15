@component('components.modal', ['id' => 'modalEgreso' , 'size' => 'modal-md' , 'title' => "Registrar Egreso" ])

  @slot('body')
    @include('cajas.partials.form_egreso')
  @endslot 

  @slot('footer')
    <div class="botones_div">
      <a 
        data-direction="{{ route('cajas.egresos_create' , [ 'id_caja' => $caja->CajNume]) }}"
        data-direction_update="{{ route('cajas.egresos_edit' , [ 'id_caja' => $caja->CajNume]) }}"          
        class="btn pull-left btn-success btn-flat save">
        <span class="fa fa-envelope"> </span> Guardar</a>
    </div> 
  @endslot
@endcomponent 
