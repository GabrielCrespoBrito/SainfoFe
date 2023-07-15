@component('components.modal', ['id' => 'modalApertura' , 'size' => 'modal-sm' , 'title' => "Monto de apertura" ])

  @slot('body')
    @include('cajas.partials.form_caja')
  @endslot 
  
  @slot('footer')
    <div class="botones_div">
      <a data-url="{{ $url }}" class="btn pull-left btn-success btn-flat save">       
      <span class="fa fa-save "> </span> Guardar </a>
    </div> 
  @endslot

@endcomponent 

