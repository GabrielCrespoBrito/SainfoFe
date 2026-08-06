@php
    $empresa = get_empresa();
    $zonas = $empresa->zonas();
@endphp

<!-- Almacen -->
<div class="filtro">
    <div class="cold-md-12">
        <fieldset class="fsStyle">
            <legend class="legendStyle">Zonas </legend>
            <div class="row" id="demo">

                <div class="col-md-12">
                    <select type="text" name="zona" class="form-control input-sm flat text-center">
                        <option value="todos"> -- TODOS -- </option>
                        @foreach ($zonas as $zona)
                            <option value="{{ $zona->ZonCodi }}"> {{ $zona->ZonNomb }} </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </fieldset>
    </div>
</div>
<!-- Fechas -->
