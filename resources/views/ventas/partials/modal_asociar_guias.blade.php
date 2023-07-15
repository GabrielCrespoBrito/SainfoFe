@php
  $guias = $guias ?? [];
@endphp

@component('components.modal', [ 'id' => 'modalAsocGuia', 'size' => 'modal-md', 'title' => 'Guia remisión'])

  @slot('body')
    <div class="div-asoc-guia">
      
      @if($create)
      <div class="box-guias">
        <div class="row plantilla-select-guia" style="display:none">
          <div class="col-md-12">
            <div class="form-group col-md-12" style="overflow:hidden">
              <div class="input-group">
                <div class="fixed_position">
                  <select style="position: absolute;border: none;padding-top: 3px;" data-settings="{{ json_encode([ 'placeholder' => 'Buscar Guia' , 'theme' => 'default container-cliente-search' ]) }}" data-url="{{ route('guia.searchJson') }}" name="guia_asoc[]" class="form-control input-sm"></select>
                </div>
                <span class="input-group-addon" style="border:none;padding-top:3px">
                  <a href="#" class="btn btn-xs btn-delete-new btn-danger"> <span class="fa fa-minus"></span> </a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <a href="#" data-url="{{ route('guia.searchJson') }}" class="btn-add-new btn btn-block btn-flat btn-default"> <span class="fa fa-plus"></span> Agregar </a>
        </div>
      </div>


      @elseIf( $guias )
      <div class="box-guias">
        <div class="row">

          @foreach( $guias as $guia )
          <div class="col-md-12">
            <a style="overflow: hidden;margin-bottom:10px" class="btn btn-flat btn-block btn-default" target="_blank" href="{{  route('guia.edit',  $guia->GuiOper) }}">  
              <strong>  {{ $guia->guia->numero() }}  </strong> |
              {{ $guia->guia->GuiFemi }} |
              {{ $guia->guia->cli->PCRucc  }}  {{ $guia->guia->cli->PCNomb  }}
            </a>
          </div>
          <br>
          @endforeach
        </div>

        
      </div>


      @endif

    </div>
  @endslot

@endcomponent