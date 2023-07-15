<div class="row">

  {{-- Titulo --}}
  <div class="col-md-12">
    <h4 class="text-center usuario-documento-titulo"> Formato de impresiòn </h4>
  </div>

  {{-- Plantilla  --}}
  @foreach( $plantillas_group as $tipo => $plantillas_tipo )
  <div class="row-tipo" data-tipo="{{ $tipo }}">  
    @foreach( $plantillas_tipo as $formato => $plantillas )
    <div class="form-group col-md-4">
      <div class="input-group">
        <label for="plantilla_{{ $formato }}"> Plantilla Formato: {{ strtoupper($formato) }} *</label>
        <select id="plantilla_{{ $formato }}" class="form-control formato_{{ $formato }}" name="formato_{{ $formato }}">
          @foreach( $plantillas as $plantilla )
          <option data-view="{{ $plantilla->vista }}" {{ $usuario_documento->{$usuario_documento->getIdField($formato)} == $plantilla->id ? 'selected=selected' : '' }} data-route="{{ route('empresa.generate_plantilla_pdf', ['empresa_id' => $usuario_documento->empcodi, 'plantilla_id' => $plantilla->id ] ) }}" value="{{ $plantilla->id }}">{{ $plantilla->nombre }}</option>
          @endforeach
        </select>
        <span style="padding: 0;border: 0;margin: 0;vertical-align: bottom;" class="input-group-addon"> <a href="#" class="btn-show-pdf btn-md btn btn-flat btn-default"> <span class="fa fa-eye"></span> </a> </span>
      </div>
    </div>
    @endforeach
  </div>
  @endforeach
  {{-- /Plantilla  --}}  


</div>