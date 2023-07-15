<div id="generic_price_table">
    
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <div class="price-heading clearfix">
        <h1>Has elegido el plan <span class="plan-nombre"> {{ $plan_duracion->nombreCompleto() }} </span> </h1>
        <p> 
          Querido <strong> {{ auth()->user()->nombre() }} </strong>, has seleccionado el  <strong> {{ $plan_duracion->nombreCompleto() }} </strong>, solo tienes que darle click al botonde Aceptar, por la cual se generara una orden de pago la cual recibieras en tu correo para poder asi cancelar el importe por los medios de pagos disponibles. Al confirmar estaras aceptando los <a href="#modalTerminos" data-toggle="modal"> Terminos y condiciones del servicio. </a>
        </p>
      </div>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-offset-4 col-md-4">
      
      <div class="header">
        <table class="table table-orden-resumen">
          <thead>
            <tr>
              <th class="" colspan="2"> <h2> Resumen de orden de pago </h2> </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td  class="" colspan="2"> 
                  <span class="producto-title">Producto: </span>   
                  <strong class="text-uppercase"> Plan {{ $plan_duracion->nombreCompleto() }} </strong> 
              </td>
            </tr>
            <tr class="">
              <td  class=""> Base </td>
              <td class="value"> {{ decimal($plan_duracion->base) }} </td>
            </tr>
            <tr class=""">
              <td  class=""> IGV </td>
              <td class="value"> {{ decimal($plan_duracion->igv) }} </td>
            </tr>
            <tr class="descuento">
              <td  class=""> Desc </td>
              <td class="value"> - {{ decimal($plan_duracion->descuento_value ) }} </td>
            </tr>

            <tr class="total">
              <td  class=""> <strong> Total </strong> </td>
              <td class="value"> <strong> {{ decimal($plan_duracion->total) }} </strong> </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="aceptar">
      <a id="confirm-orden" href="{{ route('suscripcion.ordenes.store', $plan_duracion->id ) }}" class="btn btn-success btn-block"> Aceptar </a>

      <a id="confirm-orden-load" style="display:none" href="#" disabled class="btn disabled btn-default btn-block"> <span class="fa fa-spin spinner fa-spinner"> </span>  Cargando </a>




      </div>

    </div>
  </div>

</div>