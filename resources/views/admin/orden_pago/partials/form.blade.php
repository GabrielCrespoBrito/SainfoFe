@php 
  $isAdmin = $isAdmin ?? false;
@endphp

<div class="orden-show">
  <div class="row">

      <div class="col-md-6 orden-details">
        <div class="title">
          Información:
        </div>

        <div class="">
          <span class="info-text"> Empresa: </span> <span class="info-value">  
            <strong>  {{ $orden->empresa->EmpLin1 . ' ' .  $orden->empresa->EmpNomb }} </strong>
            </span>
        </div>

        <div class="">
          <span class="info-text">  Estado: </span>  <span class="info-value status-{{ $orden->estatus }}"> {{ $orden->estatus }} </span>
        </div>
    
        <div class="">
          <span class="info-text">  Fecha: </span>  <span class="info-value"> :{{ $orden->fecha_emision }} </span>
        </div>

        <div class="">
          <span class="info-text">  Producto: </span>  <span class="info-value"> :{{ $orden->planduracion->nombreCompleto() }} </span>
        </div>        
      </div>



      <div class="col-md-12 orden-totals">

        <div class="title">
          Importes
        </div>

          <table class="table table-orden col-md-4">
            <tbody>
              <tr>
                <td> Base </td> 
              <td class="value"> {{ $orden->base }} </td>
            </tr>

            <tr>            
              <td> IGV </td> 
              <td class="value"> {{ $orden->igv }} </td>
            </tr>
            
            <tr>
              <td> Descuento </td>
              <td class="text-red value"> -{{ $orden->descuento_value }} </td>
            </tr>
            
            
            <tr class="total">
              <td> Total </td>
              <td class="value"> {{ $orden->total }} </td>
            </tr>
            
          </tbody>
        </table>
        
      </div>


      @if($isAdmin)

      @if( ! $orden->isPagada() )

      <div class="col-md-12 orden-resources">
        <div class="title">
          Acciones
        </div>      
        <div class="title estado">
          <a href="{{ route('admin.suscripcion.ordenes.activar', $orden->id ) }}" class="btn btn-primary btn"> <span class="fa fa-check"></span> Habilitar </a>

        </div>              
      </div>

      @endif


      @else


      <div class="col-md-12 orden-resources">
        <div class="title">
          Detalle
        </div>      
        <div class="title estado">
          <a href="{{ route('admin.suscripcion.ordenes.pdf', $orden->id ) }}" class="btn btn-primary btn"> <span class="fa fa-"></span> Descargar Orden de Pago </a>

        </div>              
      </div>

      @endif


  </div>
</div>