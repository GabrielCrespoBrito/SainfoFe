<div class="row">
  <div class="col-md-12 mt-x10">
        <div class="row">
          <div class="col-md-12">
            <p class="title">Actualizar Costos Reales </p>
          </div>
          <div class="row pl-x10 pr-x10">
            <div class="col-md-12">
              <form action="{{ route('empresa.update_costos_reales', $empresa->id()) }}" method="get">
                {{ csrf_field() }}
                <button class="btn btn-default btn-flat" type="submit"> Actualizar </button>
              </form>
            </div>
          </div>
        </div>
  </div>
</div>



