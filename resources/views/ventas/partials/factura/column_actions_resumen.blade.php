@php
  $idPopover = "myPopover" . str_random(10);
@endphp

<button class="btn-xs btn-popover btn btn-primary" data-toggle="popover-x" data-target="#{{$idPopover}}" data-id="{{ $boleta->NumOper }}"  data-placement="left left-bottom"> <span class="fa fa-eye"></span> </button>


<div id="{{$idPopover}}" style="width: 400px" class="popover mypopover popover-default in left left-top">
  <div class="arrow"></div>
  <h3 class="popover-title"><span class="close pull-right" data-dismiss="popover-x">×</span>Acciones</h3>
  <div class="popover-content">
    <div class="contenido">
      <p class="title">Descargas </p>
      <table class="table table-sm">
        <tr>
          {{-- <td class="nombre-atributo"> PDF </td> --}}
          <td class="value-atributo"> 
            @if( $boleta->DocCEsta == "0" )
            <a href="{{ route('boletas.resource' , [ 'id' =>  $resumen->NumOper , 'docnume' =>  $resumen->DocNume , 'tipo' => 'pdf'] ) }}" target="_blank" class="btn btn-block btn-xs btn-primary"> 
              <span class="fa fa-file-pdf-o">  </span> PDF </a>
            @else
            <a href="#" class="btn text-left disabled btn-block btn-xs btn-default"> 
              <span class="fa fa-file-pdf-o">  </span> PDF - No disponible </a>
            @endif
           </td>
        </tr>
        <tr>
          {{-- <td class="nombre-atributo"> CDR </td> --}}
          <td class="value-atributo"> 
            @if( $boleta->DocCEsta == "0" )
            <a href="{{ route('boletas.resource' , [ 'id' => $resumen->NumOper , 'docnume' => $resumen->DocNume , 'tipo' => 'xml'] ) }}" target="_blank" class="btn btn-xs btn-block btn-primary"> 
              <span class="fa fa-file-zip-o"> </span> CDR </a>
            @else
            <a href="#" class="btn disabled btn-block btn-xs btn-default"> 
              <span class="fa fa-file-zip-o">  </span> CDR - No disponible </a>
            @endif
           </td>
           </td>
        </tr>

      </table>
    </div>

    <div class="contenido">
      <p class="title"> Estado en la sunat  </p>
      <table class="table table-sm">
        <tr>
          <td class="nombre-atributo"> Codigo </td>
          <td class="value-atributo"> {{ $resumen->DocCEsta }} </td>
        </tr>
        <tr>
          <td class="nombre-atributo"> Descripcion </td>
          <td class="value-atributo"> {{ $resumen->DocDesc }}  </td>
        </tr>
      </table>
    </div>

    <div class="contenido">
      <p class="title"> Acciones  </p>

      <table class="table table-sm">
        
      @if( auth()->user()->isAdmin() )
      <tr>
        <td class="value-atributo"> <a href="{{ route('boletas.validar_resumen' , ['numoper' => $boleta->NumOper, 'docnume' => $boleta->DocNume])}}" class="btn btn-xs btn-success btn-block"> <span class="fa fa-pencil "></span> Validar Resumen </a>  </td>
      </tr>
      @endif
      
        <tr>
          <td class="value-atributo"> <a href="{{ route('boletas.agregar_boleta' , ['numoper' => $boleta->NumOper, 'docnume' => $boleta->DocNume])}}" class="btn btn-xs btn-default btn-block"> <span class="fa fa-pencil "></span> Modificar </a>  </td>
        </tr>

        <tr>
          <td class="value-atributo"> <a href="#" class="btn btn-xs btn-block btn-danger"> <span class="fa fa-trash"></span> Eliminar</a>  </td>
        </tr>
      </table>
    </div>

  </div>

</div>
