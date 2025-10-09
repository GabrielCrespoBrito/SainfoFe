<div class="row">
  <div class="col-md-12 mt-x10">
    <div class="form-clear-tables">
      <form method="POST" action="{{ route('admin.empresa.update_clientes_info', [ 'id' => $empresa->empcodi] ) }}">


        @csrf
        <div class="row">
          <div class="col-md-12">
            <p class="title">Actualizar Informacion de Clientes </p>
          </div>



          <div class="col-md-3 mt-x10">
            <p> Tipo de Documento </p>
            <select name="tipo_documento" class="form-control">
                <option value="6"> Ruc</option>
                <option value="6-20"> Ruc (Empieza por 20)</option>
                <option value="1"> Dni</option>
                <option value="all"> Todos </option>
            </select>
          </div>

          <div class="col-md-3 mt-x10">
            <p> Tipo de Informacion a Actualizar </p>
            <select name="tipo_informacion" class="form-control">
              <option value="retencion"> Agente de retencion</option>
              <option value="informacion"> Informacion (Direccion, Ubigeo, Razon Social)</option>
              <option value="all"> Todo</option>
            </select>
          </div>

          <div class="col-md-6 mt-x10">
          <p> &nbsp; - </p>
            <button type="submit" name="enviar" class="btn btn-primary btn-flat">Actualizar información </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
