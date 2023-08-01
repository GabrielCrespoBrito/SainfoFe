@php

$planes = App\Models\Suscripcion\PlanDuracion::where('empresa_id', $empresa->id())->get();

$plan_default = $planes->last();
@endphp

{{-- @dd( $planes ) --}}

<form action="{{ route('admin.suscripcion.ordenes.store_escritorio', ['empresa_id' => $empresa->id() ]) }}" method="post">

  {{ csrf_field() }}

  <div class="row empresa-parametros">

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon ">Plan</span>
        <select class="form-control input-sm" name="plan">
          @foreach( $planes as $plan )
            <option {{ $plan->id == $plan_default->id ? 'selected' : '' }}  data-info="{{ json_encode($plan) }}"  value="{{ $plan->id  }}">{{ $plan->codigo  }} </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon ">Estatus</span>
        <select class="form-control input-sm" name="estatus">
          <option value="pendiente"> Pendiente</option>
          <option value="pagada"> Pagada</option>
        </select>
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon ">Fecha Suscripción</span>
        <input class="form-control input-sm" type="date" name="fecha_final">
        </select>
      </div>
    </div>

    

  </div>
  
  <div class="row empresa-parametros">
    
    <div class="form-group col-md-3 ">
      <div class="input-group">
        <span class="input-group-addon">Descuento</span>
        <input class="form-control input-sm" name="descuento_value" value="{{ $plan_default->descuento_value }}"  type="text">
      </div>
    </div>
  
    <div class="form-group col-md-3">
      <div class="input-group">
        <span class="input-group-addon">Base</span>
        <input class="form-control input-sm" required name="base" value="{{ $plan_default->base }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="input-group">
        <span class="input-group-addon">Igv</span>
        <input class="form-control input-sm" required name="igv" value="{{ $plan_default->igv }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="input-group">
        <span class="input-group-addon">Total</span>
        <input class="form-control input-sm" required name="total" value="{{ $plan_default->total }}" type="text">
      </div>
    </div>


  </div>


    <div class="row">
      <div class="form-group col-md-12">
        <button class="btn btn-primary btn-flat" type="submit"> <span class="fa fa-save"></span> Guardar</button>
      </div>
    </div>

  </form>