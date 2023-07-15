@component('components.modal', ['id' => 'modalIngreso' , 'size' => 'modal-md' , 'title' => "Registrar ingreso" ])


  @slot('body')
    @include('cajas.partials.form_ingreso')
  @endslot 

  @slot('footer')
      <div class="botones_div">
        <a 
        	data-adicioonal_info="" 
        	class="btn pull-left btn-success btn-flat save" 
        	data-direction="{{ route('cajas.ingreso_store' , [ 'caja_id' => $caja->CajNume]) }}"
        	data-direction_update="{{ route('cajas.ingreso_update' , [ 'caja_id' => $caja->CajNume]) }}"
        >    
        <span class="fa fa-save"></span> Guardar </a>
    </div> 

  @endslot

@endcomponent