@extends('layouts.master')

@section('js')
  <script type="text/javascript">
    var mes = 0;
    var active_or_disable_;
    var active_ordisable_tr;
    var table;
    var fecha_actual = '{{ date('Y-m-d') }}';
    var url_quitar_resumen = "{{ route('boletas.eliminar_resumen') }}";
  </script>   
  @add_libreria('popover|js')
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>      
  <script src="{{ asset('js/ventas/resumen.js') }}"> </script>  
@endsection

@section('css')
  @add_libreria('popover|css')
@endsection

@section('titulo_pagina', 'Resumenes Diarias de boletas')

@section('contenido')


<div class="col-md-8 ventas">

  <div class="row">

    <div class="col-md-4 no_pr">                
      <select name="mes" class="form-control input-sm text-center">
        @php
          $meses = get_mes();         
        @endphp
        @foreach( $meses->reverse() as $mes )
          <option 
          {{ $mes->mescodi == $fecha ? 'selected=selected' : '' }} 
          value="{{ route('boletas.resumen_dia' , [
            'fecha' => $mes->mescodi , 
            'tipo_resumen' => $tipo_resumen, 
            'local' => $localDefault ]) }}"> {{ $mes->mesnomb }} </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3 no_pr">                
      <select name="tipo_resumen" class="form-control input-sm text-center">  
          <option {{ $tipo_resumen == "R" ? "selected" : "" }} value="{{ route('boletas.resumen_dia' , [ 'fecha' => $fecha, 'tipo_resumen' => 'R' ] ) }}"> Resumen </option>
          <option {{ $tipo_resumen == "A" ? "selected" : "" }} value="{{ route('boletas.resumen_dia', [ 'fecha' => $fecha , 'tipo_resumen' => 'A' ,  'local' => $localDefault  ]) }}"> Anulacion </option>       
      </select>
    </div>    

    <div class="col-md-5 no_pr">                
      <select name="local" class="form-control input-sm text-center">  
        @foreach( auth()->user()->locales as $local )
          <option 
            value="{{ route('boletas.resumen_dia' , [ 'fecha' => $fecha, 'tipo_resumen' => $tipo_resumen , 'local' => $local->loccodi ] ) }}"
            {{ $local->loccodi == $localDefault ? 'selected=selected' : '' }}> {{ $local->local->LocNomb }} 
          
          </option>

        @endforeach
      </select>
    </div>


  </div>

</div>

<div class="col-md-4 acciones-div ww">

  <a href="#" data-toggle="tooltip" title="Eliminar" class="btn btn-danger btn-flat pull-right eliminar-accion"> <span class="fa fa-trash"></span>  </a>

  <a data-href="#" href="#" data-toggle="tooltip" title="Modificar" class="btn btn-default btn-flat pull-right modificar-accion"> <span class="fa fa-pencil"></span> </a>

  <a href="{{ route('boletas.agregar_boleta') }}" data-toggle="tooltip" title="Nueva" class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> </a>

</div>

<div class="col-md-12 col-xs-12 content_ventas div_table_content" style="overflow-x: scroll;">
  <table style="width: 130% !important;" class="table oneline v-t sainfo-table sainfo-noicon sainfo-table table_boletas_mes" id="datatable">
  <thead>
    <tr>
      <td> N° Oper </td>
      <td> Numero </td>
      <td> Fecha </td>    
      <td> Fecha Doc</td>          
      <td> Documentos </td>    
      <td> XML </td>
      <td> CDR </td>
      <td> PDF </td>
      <td> Estado </td>      
      <td> Estado Sunat </td>
      <td> Fecha envio </td>
      <td> Ticket </td>
      <td> Codigo hash </td>
      <td> Acción </td>
    </tr>
  </thead>
  <tbody>
  @foreach( $boletas as $boleta  )
  <tr class="estado_{{ $boleta->DocCEsta }}" data-url="{{ route('boletas.agregar_boleta' , [ 'id' => $boleta->NumOper, 'docnume' => $boleta->DocNume ] ) }}">
    <td> <a target="_blank" href="{{ route('boletas.agregar_boleta' , ['numoper' => $boleta->NumOper, 'docnume' => $boleta->DocNume])}}"> {{ $boleta->NumOper }} </a> </td>      
    <td>{{ $boleta->DocNume }}</td>
    <td>{{ $boleta->DocFechaE }}</td>
    <td>{{ $boleta->DocFechaD }}</td>
    <td class="text-oneline"> {{  $boleta->DocDesc }}  </td>
    <td>{{ $boleta->DocXML }}</td>
    <td>{{ $boleta->DocCDR }}</td>
    <td>{{ $boleta->DocPDF }}</td>
    <td>{{ $boleta->DocCEsta }}</td>    
    <td>{{ $boleta->DocEstado }}</td>        
    <td>{{ $boleta->DocFechaEv }}</td>
    <td>{{ $boleta->DocTicket }}</td>
    <td>{{ $boleta->DocCHash }}</td>
    <td> @include('ventas.partials.factura.column_actions_resumen' , [ 'resumen' => $boleta ]  ) </td>



  </tr>
  @endforeach
  </tbody>
  </table>
</div>


@endsection

