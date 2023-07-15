@extends('layouts.master')

@section('js')
  <script src="{{ asset('plugins/download/download.js') }}"> </script>  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>  
  <script type="text/javascript">
    function download_file(data)
    {
      let contenido = "data:application/zip;base64," + data.contenido;
      download( contenido , "Reporte_resumen.pdf", "application/pdf" );  
    }

    $(document).ready(function(){
      $(".generar_reporte").on('click' , function(){
        let data = { id_caja : $("[name=id_caja]").val() };
        let url = "{{ route('cajas.resumen_pdf' , $caja->CajNume) }}";
        let funcs = { success : download_file };
        cl( data, url, funcs );
        ajaxs( data , url , funcs );
      });
    })
  </script>
@endsection

<?php $class_adicional = "caja_resumen"; ?>

@section('bread')
  <li><a href="{{ route('cajas.index') }}"> Cajas</a></li>
  <li> Movimientos </li>  
@endsection


@section('titulo_pagina')
  Caja <span class="caja_number">{{ $caja->CajNume }}</span> 
  <span class="caja_estado {{ $caja->CajEsta == "Ap" ? 'aperturada'  : 'cerrada' }}"> 
    {{ $caja->CajEsta == "Ap" ? "Aperturada" : "Cerrada" }}
  </span>
@endsection

@section('contenido')

<input type="hidden" name="id_caja" value="{{ $caja->CajNume }}">

<div class="row">
  <div class="col-md-12">
    <a href="#" class="btn btn-flat btn-default pull-right generar_reporte">Reporte</a>
    <a href="#" class="btn btn-flat btn-default pull-right generar_reporte">Reporte de ventas</a>
    <a href="#" class="btn btn-flat btn-default pull-right generar_reporte">Reporte de compras </a>
  </div>
</div>



<div class="row group_info">

  <div class="col-md-4">  
    <div class="input-group">
      <div class="input-group-addon">Usuario</div>
      <input type="text" class="form-control" disabled value="{{ $caja->User_Crea }}">
    </div>
  </div>  

  <div class="col-md-4">
    <div class="input-group">
      <div class="input-group-addon">Fecha apertura</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajFech }}">
    </div>
  </div>

  <div class="col-md-4">  
    <div class="input-group">
      <div class="input-group-addon">Fecha cierre</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajFecC }}">
    </div>
  </div>

</div>



{{-- Saldo  --}}

<div class="row group_info">

  <div class="col-md-2">
    <p class="form-control"> Saldo </p>
  </div>

  <div class="col-md-5">

    <div class="input-group">
      <div class="input-group-addon">S./</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajSalS }}">
    </div>

  </div>

  <div class="col-md-5">

    <div class="input-group">
      <div class="input-group-addon">USD./</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajSalD }}">
    </div>

  </div>



</div>

{{-- /Saldo --}}





<div class="row group_info">

  <div class="col-md-2">
    <p class="form-control"> Ingresos </p>
  </div>

  <div class="col-md-4">

    <div class="input-group">
      <div class="input-group-addon">S./</div>
      <input type="text" class="form-control" disabled value="{{ $total_ingresos_soles }}">
    </div>

  </div>

  <div class="col-md-4">

    <div class="input-group">
      <div class="input-group-addon">USD./</div>
      <input type="text" class="form-control" disabled value="{{ $total_ingresos_dolar }}">
    </div>

  </div>

  <div class="col-md-2">
      <a target="blank" href="{{ route('cajas.movimientos', $caja->CajNume ) }}" class="btn btn-default btn-sm btn-block btn-flat"> <span class="fa fa-eye"></span> Ver Detalles</a>      
  </div>

</div>

<div class="row group_info">

  <div class="col-md-2">
    <p class="form-control"> Egresos </p>
  </div>

  <div class="col-md-4">

    <div class="input-group">
      <div class="input-group-addon">S./</div>
      <input type="text" class="form-control" disabled value="{{ $total_egresos_soles }}">
    </div>

  </div>

  <div class="col-md-4">

    <div class="input-group">
      <div class="input-group-addon">USD./</div>
      <input type="text" class="form-control" disabled value="{{ $total_egresos_dolar }}">
    </div>

  </div>

  <div class="col-md-2">
      <a target="blank" href="{{ route('cajas.movimientos', [$caja->CajNume, "egresos"] ) }}" class="btn btn-default btn-sm btn-block btn-flat"> <span class="fa fa-eye"></span> Ver Detalles</a>      
  </div>

</div>


<div class="row group_info">

  <div class="col-md-12 box-resumen" style="margin: 20px 0">

    <div class="titulo"> <span class="nombre">Ventas</span> </div>

    @include('reportes.partials.caja_resumen.item_informacion', 
    ['nombre'=>'FACTURA','soles'=> $ventas_data['01'][0],'dolar'=>$ventas_data['01'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'BOLETA','soles'=> $ventas_data['03'][0],'dolar'=>$ventas_data['03'][1] ])


    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA VTA','soles'=> $ventas_data['07'][0],'dolar'=>$ventas_data['07'][1] ])


    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA CRE','soles'=> $ventas_data['08'][0],'dolar'=>$ventas_data['08'][1] ])

  </div>

</div>


<div class="row group_info">

  <div class="col-md-12 box-resumen">

    <div class="titulo"> 
      <span class="nombre">Compras</span>
      <!-- <span> S./ <span class="valores soles">1.00</span> </span> -->
      <!-- <span> USD./ <span class="valores dolar">5.00</span> </span> -->
    </div>

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'FACTURA','soles'=>'0.00','dolar'=>'0.00' ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'BOLETA','soles'=>'0.00','dolar'=>'0.00' ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA VTA','soles'=>'0.00','dolar'=>'0.00' ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA CRE','soles'=>'0.00','dolar'=>'0.00' ])

  </div>

</div>

@endsection