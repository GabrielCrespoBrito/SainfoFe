<div class="row" style="border-left:3px solid #dfdfdf">

    <div class="col-md-3 form-group">
        <label for="form-telefono"> Serie </label>
        <div class="input-group">
        <input 
          maxlength="3"
          type="text"
          name="serie"
          id="input-serie"
          class="form-control text-uppercase"
          readonly
          data-initial="{{ $serie }}"
          value="{{ old('serie',$serie) }}">
          @if($isCreate)
          <span class="input-group-addon change-input-readonly change-serie cursor-pointer" data-target="input-serie"> <span class="fa fa-pencil"></span> </span>
          @endif
        </div>
    </div>

    {{-- Recompensa hacer cronograma para hoy --}}
    <div class="col-md-12 form-group">
      <table id="table-series" class="table table-responsive table-bordered">
        <tbody>
          @foreach( $series as $serie )
          <tr class="serie-info" data-info="{{ json_encode($serie) }}">
            <td>{{ $serie['nombre'] }}</td>
            <td class="serie-nombre">{{ $serie['serie'] }}</td>
            <td>
              @if($isCreate)
              <input type="number" class="form-control input-sm" min="0" name="{{ $serie['codigo'] }}" readonly="readonly" id="{{ $serie['codigo'] }}" value="{{ $serie['correlativo'] }}">
              @else 
                {{ $serie['correlativo'] }}
              @endif
            </td>

            @if($isCreate)
            <td>
              <a href="#" data-target="{{ $serie['codigo'] }}" class="btn-edit-correlative change-input-readonly btn btn-xs btn-default btn-flat"> <span class="fa fa-pencil"></span> </a>
            </td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{--  --}}

</div>
