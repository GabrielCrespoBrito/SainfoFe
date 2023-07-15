<div class="row">

    <div class="col-md-4 form-group">
        <label for="form-nombre"> Nombre (*)</label>
        <input type="text" id="form-nombre" class="form-control input-sm text-upper text-uppercase" required placeholder="Nombre" name="LocNomb" value="{{ old('LocNomb', $model->LocNomb) }}">
    </div>

    <div class="col-md-8 form-group">
        <label for="form-direccion"> Direcciòn (*)</label>
        <input type="text" id="form-direccion" class="form-control input-sm" required placeholder="Direcciòn" name="LocDire" value="{{ old('LocDire', $model->LocDire) }}">
    </div>

</div>

<div class="row">
    
    <div class="form-group col-md-6">
        <label for="form-ubigeo" class="">Ubigeo (*)</label>
        <div class="fixed_position">
        <select placeholder="Buscar Ubigeo" data-text="{{ optional($model->ubigeo)->completeName() }}" id="form-ubigeo" data-id="{{ optional($model->ubigeo)->ubicodi }}" name="LocDist" data-url="{{ route('clientes.ubigeosearch') }}" required class="form-control input-sm select2" style="">
        </select>
        </div>
    </div>

    <div class="col-md-6 form-group">
        <label for="form-telefono"> Telefono </label>
        <input id="form-telefono" type="text" class="form-control input-sm text-upper text-uppercase" placeholder="Telefono" name="LocTele" value="{{ old('LocTele', $model->LocTele) }}">
    </div>

</div>