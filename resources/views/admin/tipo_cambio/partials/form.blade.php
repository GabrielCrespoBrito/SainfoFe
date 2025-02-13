<div class="col-md-12"
    style="background-color: #ebebeb;
    margin-bottom: 20px;
    padding: 10px 0;
    border: 1px solid #848383;">


    <form action="{{ route('admin.tipo_cambio.store') }}" method="post">
        @csrf

        @if($message)
        <div class="col-md-12">
            <span class="fa fa-clock-o"></span> Fecha de tipo de cambio : ({{  $fecha  }})
            <span class="text-center" style="color:black; float:right; font-style:italic;margin-bottom:10px">{{ $message }}</span>
        </div>
        @endif

        <div class="form-group col-md-3 pb-2">
            <label for="fecha">Fecha </label>
            <div class="input-group">
            <input type="date" data-route="{{ route('admin.tipo_cambio.index') }}" class="form-control" name="fecha" value="{{ $fecha }}" id="fecha"
                placeholder="Fecha">
            <span class="cursor-pointer input-group-addon buscar-fecha"><i class="fa fa-search"></i></span>

            </div>
        </div>

        {{--  --}}
        <div class="form-group col-md-3">
            <label for="tccompra">Compra</label>
            <input type="text" class="form-control" value="{{ $compra }}" name="compra" id="tccompra"
                placeholder="Compra">
        </div>
        <div class="form-group col-md-3">
            <label for="labeltcventa">Venta</label>
            <input type="text" class="form-control" value="{{ $venta }}" name="venta" id="labeltcventa"
                placeholder="Venta">
        </div>
        <div class="form-group col-md-3">
            <label>-</label>
            <div class="text-right">

                <button type="submit" {{ $saveable ? '' : 'disabled' }} class="btn btn-primary">Guardar</button>
            </div>
        </div>

    </form>
</div>
