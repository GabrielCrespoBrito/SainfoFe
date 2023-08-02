<div class="row">
  <div class="col-md-12 mt-x10">
        <div class="row">
          <div class="col-md-12">
            <p class="title">Enviar Email de notificacion de Suscripción Por Vencerse  </p>
          </div>

            <div class="col-md-12">
              <form action="{{ route('admin.empresa.enviar_email_porvenc_suscripcion', $empresa->id()) }}" method="get">
                {{ csrf_field() }}
                <button class="btn btn-default btn-flat" type="submit"> Enviar </button>
              </form>
            </div>
        </div>
  </div>
</div>






