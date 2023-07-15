@php
  $idRandom = str_random(10);
  $idPopover = "myPopover" . $idRandom;
  $isIngreso = $model->isIngreso();
  $routeEdit = route('guia_ingreso.edit' , $model->GuiOper  );
  $routeEdit = $isIngreso ? route('guia_ingreso.edit' , $model->GuiOper ) : route('guia.edit' , $model->GuiOper );
  $routeConsultTicket = route('guia.consultTicket' , $model->GuiOper );
  $idPopover = "myPopover" . str_random(10);
  $isCerrada =  $model->isCerrada();
@endphp

<button class="btn-xs btn-popover btn btn-primary" data-toggle="popover-x" data-target="#{{$idPopover}}" data-id="{{ $idPopover }}"  data-placement="left left-bottom"> Acciones </button>

<div id="{{$idPopover}}" style="width: 400px" class="popover mypopover popover-default in left left-top">
  <div class="arrow"></div>
  <h3 class="popover-title"><span class="close pull-right" data-dismiss="popover-x">×</span>Acciones</h3>
  <div class="popover-content">

    <!-- Descarga -->
    <div class="contenido">
      <p class="title">Descargas </p>
      <table class="table table-sm">
  
        <tr>
          <td class="value-atributo"> 

  {{--  
    <a href="{{ route('guia.pdf' , $GuiOper ) }}" target="_blank" class="btn btn-block btn-xs btn-primary"> 
    <span class="fa fa-file-pdf-o">  </span> PDF 
    </a>
  --}}

  {{--  --}}
  <div class="col-md-9 no-p pb-x4">

    <a 
      id="{{ $idRandom }}"
      href="{{ route('guia.pdf' , $GuiOper) }}"
      class="btn-flat pdf-enlace btn btn-block btn-xs btn-primary btn-flat">
      <span class="fa fa-file-pdf-o"> </span> PDF
    </a>

  </div>

  <div class="col-md-3 no_pl no_pr">
    <select data-target="{{ $idRandom }}" class="formato_pdf" style="width:100%; height: 23px;" name="formato_pdf" style="width:100%;height:1.6em">
      <option value="{{ route('guia.pdf' , [ 'id' => $GuiOper, 'formato' => 'a4' ]) }}">A4</option>
      <option value="{{ route('guia.pdf' , [ 'id' => $GuiOper, 'formato' => 'ticket' ]) }}">Ticket</option>
    </select>
  </div>
{{--  --}}


           </td>
        </tr>

    @if( ! $isIngreso )
      <tr>
          <td class="value-atributo"> 
            @if( $GuiXML )
            <a href="{{ route('guia.file' , ['type' => 'xml' , 'id' => $GuiOper] ) }}" target="_blank" class="btn text-left btn-xs btn-block btn-primary"> 
              <span class="fa  fa-file-text-o">  </span> XML  </a>
            @else
            <a href="#" class="text-left btn disabled btn-block btn-xs btn-default"> 
              <span class="fa fa-file-text-o">  </span> XML - No disponible  </a>
            @endif
           </td>
        </tr>

        <tr>
          <td class="value-atributo"> 
            @if( $GuiCDR )
            <a href="{{ route('guia.file' , [ 'type' => 'cdr' , 'id' => $GuiOper ]) }}" target="_blank" class="btn btn-xs btn-block btn-primary"> 
              <span class="fa fa-file-zip-o"> </span> CDR </a>
            @else
            <a href="#" class="btn disabled btn-block btn-xs btn-default"> 
              <span class="fa fa-file-zip-o">  </span> CDR - No disponible </a>
            @endif
           </td>
        </tr>

        <tr>
          <td class="value-atributo"> 
          @if( $fe_rpta == 0 )
          <a href="#" class="btn btn-xs btn-block btn-primary redactar"> 
            <span class="fa fa-send"> </span> Enviar por Email </a>
          @endif
         </td>
        </tr>

      </table>
    </div>    
    <!-- /Descarga -->

    <div class="contenido">
      <p class="title"> Estado en la sunat  </p>
      <table class="table table-sm">
        <tr>
          <td class="nombre-atributo"> Codigo </td>
          <td class="value-atributo"> {{ $fe_rpta }}</td>
        </tr>
        <tr>
          <td class="nombre-atributo"> Descripcion </td>
          <td class="value-atributo" title="{{ $fe_obse }}">{{ substr($fe_obse , 0 , 10) }}... </td>
        </tr>

        <tr>
          <td class="nombre-atributo"> Descripcion </td>
          <td class="value-atributo">{{ $fe_ticket }}... </td>
        </tr>

      </table>
    </div>

    @endif



    <div class="contenido">
      <p class="title"> Acciones  </p>
      <table class="table table-sm">

        <tr>
          <td class="value-atributo"> <a href="{{ $routeEdit }}" class="btn btn-xs btn-default btn-block"> <span class="fa fa-pencil "></span> Modificar</a></td>
        </tr>

        <tr>
          <td class="value-atributo"> <a href="{{ $routeConsultTicket }}" class="btn btn-xs btn-default btn-block consult-ticket"> <span class="fa fa-search "></span> Consultar Ticket </a></td>
        </tr>        

        <!--  -->
        <tr>
          @php
            $idForm = 'Anulate' . str_random(10);
          @endphp

          @if( ! $isIngreso )
            <td class="value-atributo"> 
              <a href="#" class="btn btn-xs anularBtn btn-block btn-danger"> Anular </a>
                <form method="get" id="{{ $idForm }}" action="{{ route('guia.anular' , [ 'guia_id' => $GuiOper] ) }}">
                @csrf 
                </form> 
            </td>

          @endif
        </tr>
  <!--  -->

        <tr>
          @php
            $idForm = str_random(10);
          @endphp

          @if( ! $isCerrada )
            <td class="value-atributo">             
              <a href="#" data-formid="{{ $idForm }}" class="btn btn-xs btn-block deleteBtn btn-danger"> <span class="fa fa-trash"></span> Eliminar</a>
                <form method="post" id="{{ $idForm }}" action="{{ route('guia.delete' , [ 'id' => $GuiOper] ) }}">
                @csrf
                {{method_field('DELETE')}}
                </form> 
            </td>

          @endif
        </tr>
      </table>

    </div>
    
  </div>
</div>
