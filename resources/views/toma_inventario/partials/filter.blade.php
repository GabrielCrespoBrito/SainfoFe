@php
$selected = false;
$loccodi = $locales->first()->loccodi;
@endphp
<div class="row">

  <div class="col-md-8 ventas">
    <select name="local" data-reloadtable="table" class="form-control input-sm">
      @foreach ($locales as $local)

      @php
      if( $local->defecto ){
        $loccodi = $local->loccodi;
      }
      @endphp

      <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>
        {{ $local->local->LocNomb }}
      </option>

      @endforeach
    </select>
  </div>

  <div class="col-md-4">

    <a data-href="{{ route('toma_inventario.create', ['loccodi' => 'replace' ]) }}" href="{{ route('toma_inventario.create', ['loccodi' => $loccodi  ]) }}" class="btn btn-primary pull-right  btn-flat btn-create"> <span class="fa fa-hand-pointer-o"></span> Manual </a>

    <a data-toggle="modal" data-target="#modalImport" class="btn btn-primary pull-right mr-x2 btn-flat"> <span class="fa fa-file-excel-o"></span> Excell
    
    </a>

  </div>

</div>