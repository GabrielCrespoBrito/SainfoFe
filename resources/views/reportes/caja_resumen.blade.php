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

      const $modal = $("#modalApertura");


      $(".generar_reporte").on('click' , function(){
        let data = { id_caja : $("[name=id_caja]").val() };
        let url = "{{ route('cajas.resumen_pdf' , $caja->CajNume) }}";
        let funcs = { success : download_file };
        ajaxs( data , url , funcs );
      });

      $(".generar_reporte_simplificado").on('click' , function(){
        let data = { id_caja : $("[name=id_caja]").val() };
        let url = "{{ route('cajas.resumen_simplificado_pdf' , $caja->CajNume) }}";
        let funcs = { success : download_file };
        ajaxs( data , url , funcs );
      });

    // ------
      $(".show-modalapertura").on('click' , function(e){
        e.preventDefault();


        const apertura_soles = Number($(".apertura_soles").text());
        const apertura_dolares = Number($(".apertura_dolares").text());
        
        $modal.find('[name=CANINGS]').val(apertura_soles)
        $modal.find('[name=CANINGD]').val(apertura_dolares)
        $modal.modal(true);
      });

    // ------

      $modal.on('click' , '.save', function(e){

        e.preventDefault();

        const url = $modal.find('.save').attr('data-url');

        let soles = $modal.find('[name=CANINGS]').val();
        let dolar = $modal.find('[name=CANINGD]').val();
        
        const data = {
          CANINGS : soles,
          CANINGD : dolar,
        }

        $("#load_screen").show();
        ajaxs(data, url , { 
          success: (data) => {
            notificaciones(data.message, 'success' )
            setTimeout(() => {
              location.reload();
            }, 1000)
          },
          complete: (data) => {
            console.log("complete", e)
          },          
        })


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

    <div class="btn-group">
    <button type="button" class="btn btn-default btn-flat"> <span class="fa fa-file-text-o"></span> Reporte Pagos </button>
    <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
    <li> <p class="text-center" style="color:gray"> Agrupar por </p> </li>
      <li><a target="_blank" href="{{ route('reportes.ventas_tipopago.pdf_caja', [ 'caja_id' => $caja->CajNume, 'tipo_pago' => null ] ) }}">- TODOS -</a></li>
    @foreach( $tipos_pagos as $tipo_pago )
      <li><a target="_blank" href="{{ route('reportes.ventas_tipopago.pdf_caja', [ 'caja_id' => $caja->CajNume, 'tipo_pago' => $tipo_pago->TpgCodi ] ) }}">{{  $tipo_pago->TpgNomb }}</a></li>
    @endforeach
    </ul>
    </div>


    <a target="_blank" href="{{ route('cajas.reporte_documento', [ 'id_caja' => $caja->CajNume, 'tipo' => 'compras'] ) }}" class="btn btn-flat btn-default pull-right ml-x10 "> <span class="fa fa-file-text-o"></span> Reporte Compras</a>
    

    <a target="_blank" href="{{ route('cajas.reporte_documento', [ 'id_caja' => $caja->CajNume, 'tipo' => 'ventas'] ) }}" class="btn btn-flat btn-default pull-right ml-x10 "> <span class="fa fa-file-text-o"></span> Reporte Ventas</a>
    

    <div class="btn-group pull-right">
    <button type="button" class="btn btn-default btn-flat"> <span class="fa fa-file-text-o"></span> Reporte Caja </button>
    <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
    <li><a class="generar_reporte" href="#"> Detallado </a></li>
    <li><a target="_blank" class="" href="{{ route('cajas.resumen_simplificado_pdf' , [ 'id' => $caja->CajNume, 'formato' => 'a4']) }}"> Simplificado (a4) </a></li>
    <li><a target="_blank" class="" href="{{ route('cajas.resumen_simplificado_pdf' , [ 'id' => $caja->CajNume, 'formato' => 'ticket']) }}"> Simplificado (ticket) </a></li>
    </ul>
    </div>

  </div>
</div>

@php
  $apertura = optional($caja->apertura());


  $apertura_soles = optional($apertura)->ingreso_soles();
  $apertura_dolares = optional($apertura)->ingreso_dolar();
@endphp

<div class="row group_info">

  <div class="col-md-3">  
    <div class="input-group">
      <div class="input-group-addon">Usuario</div>
      <input type="text" class="form-control" disabled value="{{ $caja->User_Crea }}">
    </div>
  </div>  

  <div class="col-md-3">
    <div class="input-group">
      <div class="input-group-addon">Dinero Apertura </div>
      <p class="form-control">


       <span> S/.
          <strong class="apertura_soles">{{ $apertura_soles }}</strong>  
       </span>

       <span class="pull-right"> USD$

          <strong class="apertura_dolares">{{  $apertura_dolares }}</strong>   

        </span>

      </p>

      {{-- const apertura_soles = $(".apertura_soles").val() --}}
      {{-- const apertura_dolares = $(".apertura_dolares").val() --}}


      <div class="input-group-addon"><a href="#" 
      class="btn btn-xs show-modalapertura"> <span class="fa fa-pencil"></span> </a></div>

    </div>


  </div>


  <div class="col-md-3">
    <div class="input-group">
      <div class="input-group-addon">Fecha apertura</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajFech }}">
    </div>
  </div>

  <div class="col-md-3">  
    <div class="input-group">
      <div class="input-group-addon">Fecha cierre</div>
      <input type="text" class="form-control" disabled value="{{ $caja->CajFecC }}">
    </div>
  </div>

</div>



{{-- Compra y Venta  --}}



<div class="row group_info">

  <div class="col-md-12 box-resumen" style="margin: 20px 0">

  <div class="titulo"> <span class="nombre">Ventas</span> </div>

  @php
    $sol = $ventas_data['01'][0]  + $ventas_data['03'][0]  + $ventas_data['07'][0]  + $ventas_data['08'][0]  + $ventas_data['52'][0];
    $dolar = $ventas_data['01'][1]  + $ventas_data['03'][1]  + $ventas_data['07'][1]  + $ventas_data['08'][1]  + $ventas_data['52'][1];
  @endphp

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'FACTURA','soles'=> $ventas_data['01'][0],'dolar'=>$ventas_data['01'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'BOLETA','soles'=> $ventas_data['03'][0],'dolar'=>$ventas_data['03'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA DEB','soles'=> $ventas_data['07'][0],'dolar'=>$ventas_data['07'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA CRE','soles'=> $ventas_data['08'][0],'dolar'=>$ventas_data['08'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA VENTA','soles'=> $ventas_data['52'][0],'dolar'=>$ventas_data['52'][1] ])

    @include('reportes.partials.caja_resumen.item_informacion', [ 'className' => 'total', 'nombre'=>'TOTAL VENTA','soles'=> $sol  ,'dolar'=> $dolar ])

  </div>

</div>


<div class="row group_info mb-x10">

  <div class="col-md-12 box-resumen">

    <div class="titulo"> 
      <span class="nombre">Compras</span>
      <!-- <span> S./ <span class="valores soles">1.00</span> </span> -->
      <!-- <span> USD./ <span class="valores dolar">5.00</span> </span> -->
    </div>

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'FACTURA','soles'=> $compras_data['01'][0] ,'dolar'=> $compras_data['01'][1] ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'BOLETA','soles'=> $compras_data['03'][0] ,'dolar'=> $compras_data['03'][1] ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA VTA','soles'=> $compras_data['07'][0] ,'dolar'=> $compras_data['07'][1] ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA CRE','soles'=> $compras_data['08'][0] ,'dolar'=> $compras_data['08'][1] ])

  @include('reportes.partials.caja_resumen.item_informacion', ['nombre'=>'NOTA VENTA','soles'=> $compras_data['52'][0] ,'dolar'=> $compras_data['52'][1] ])

  @php
    $sol = $compras_data['01'][0]  + $compras_data['03'][0]  + $compras_data['07'][0]  + $compras_data['08'][0]  + $compras_data['52'][0];

    $dolar = $compras_data['01'][1]  + $compras_data['03'][1]  + $compras_data['07'][1]  + $compras_data['08'][1]  + $compras_data['52'][1];    
  @endphp

  @include('reportes.partials.caja_resumen.item_informacion', ['className' => 'total' , 'nombre'=>'TOTAL COMPRA','soles'=> $sol  ,'dolar'=> $dolar ])


  </div>

</div>

{{-- Compra y Venta --}}




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


  @include('cajas.partials.modal_caja' , [ 'url' => route('cajas.dinero_apertura', $caja->CajNume) ] )

@endsection


