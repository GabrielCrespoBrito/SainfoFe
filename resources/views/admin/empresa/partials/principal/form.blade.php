@php
  $basic_modify = $basic_modify ?? false;
  $modify_sol = $modify_sol ?? true;
  $route = $basic_modify ? route('empresa.update') : route('empresa.store_parametros');
  // dd( $basic_modify );
@endphp


<div class="empresa-parametros">

  <form action="{{ $route }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="info-empresa">	
      @include('empresa.partials.principal.data_empresa', ['basic_mofiy' => $basic_modify])
    </div>
    
    @if( $modify_sol )
      <div class="info-parametros">	
        @include('empresa.partials.principal.data_parametros') 
      </div>
    @endif

    @include('empresa.partials.principal.botones') 

  </form>

  <form id="form-delete" method="post" style="display: none" action="#">   
    @method('DELETE') @csrf
  </form>

</div>