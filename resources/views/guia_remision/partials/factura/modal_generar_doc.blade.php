@php
  $title = $isIngreso ? 'Generar Compra' :  'Generar Venta';
  $seriesBoleta = '';
  $seriesFactura = '';
  $hasSeries = isset($series);
  
  if( $hasSeries ){
    $seriesFactura = $series->where('id', '01')->first()['series'];
    $seriesBoleta = $series->where('id', '03')->first()['series'];
  }
@endphp

@component('components.modal', ['id' => 'modalGenerarDoc', 'title' => $title  ])

@slot('body')

  @include('partials.errors_html')
  <form class=" focus-green" action="{{ route('guia.generar_doc' , $guia->GuiOper ) }}" method="post">		
  @csrf
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Fecha</span>
        <input class="form-control datepicker" required="required" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ date('Y-m-d') }}" name="gen_fecha" type="text" value="{{ date('Y-m-d') }}">     
    </div>
  </div>

  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Tipo Documento </span>
        <select class="form-control" name="gen_tdoc" required="required">
          <option value="01" selected data-series={{ json_encode($seriesFactura) }}> Factura </option>
          <option value="03" data-series={{ json_encode($seriesBoleta) }}> Boleta </option>
        </select>     
    </div>
  </div>

  <div class="form-group col-md-12">  
    <div class="input-group">

      @if( ! $isIngreso)
        <span class="input-group-addon"> Serie </span>
          <select class="form-control" required="required" name="gen_serie">
            @foreach( $seriesFactura as $serie  )
              @php 
                if( $loop->first ){
                  $correlativo = $serie['nuevo_codigo'];
                }
              @endphp
              <option data-info={{ json_encode($serie) }} value="{{ $serie['id'] }}"> {{ $serie['id'] }} </option>
            @endforeach             
          </select>        
        <span class="input-group-addon"> Número </span>
          <input class="form-control correlative-doc-generad" readonly value="{{  $correlativo }}">
        </span>
      @else
        <span class="input-group-addon">Serie</span>
        <input class="form-control" required="required" maxlength="4" name="gen_serie"  type="text" value=""> 
          <span class="input-group-addon"> Número </span>
            <input class="form-control" required="required" maxlength="10" name="gen_num" type="text" value="">
          </span>
      @endif
    </div>
  </div>

  <div>
    <div class="col col-md-12">
      <button type="submit" class="btn btn-success"> Generar Doc </button>
    </div>
  </div>
</form>
  @include('partials.errores')
@endslot

@endcomponent