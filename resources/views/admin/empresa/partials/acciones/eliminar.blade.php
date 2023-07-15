<div class="row">
  <div class="col-md-12">
    <div class="form-clear-tables">
      <form method="POST" action="{{ route('admin.empresa.delete_data', [ 'id' => $empresa->empcodi] )}}">
        @csrf
        <div class="row">
          <div class="col-md-12">
            <p class="title">Eliminar información</p>
          </div>
          <div class="col-md-12">
            <label for="ventas"><input type="checkbox" checked="checked" value="ventas" name="eliminar[]" id="ventas"> Ventas </label>
            <label for="compras"><input type="checkbox" checked="checked" value="compras" name="eliminar[]" id="compras"> Compras </label>
            <label for="guias"><input type="checkbox" checked="checked" value="guias" name="eliminar[]" id="guias"> Guias </label>
            <label for="ingresos"><input type="checkbox" checked="checked" value="ingresos" name="eliminar[]" id="ingresos"> Ingresos </label>
            <label for="egresos"><input type="checkbox" checked="checked" value="egresos" name="eliminar[]" id="egresos"> Egresos </label>
            <label for="productos"><input type="checkbox" checked="checked" value="productos" name="eliminar[]" id="productos"> Productos </label>
            <label for="marcas"><input type="checkbox" checked="checked" value="marcas" name="eliminar[]" id="marcas"> Marca </label>
            <label for="familias"><input type="checkbox" checked="checked" value="familias" name="eliminar[]" id="familias"> Familias </label>
            <label for="grupos"><input type="checkbox" checked="checked" value="grupos" name="eliminar[]" id="grupos"> Grupos </label>
          </div>
          <div class="col-md-12 mt-x10">
            <button type="submit" name="enviar" class="btn btn-primary btn-flat">Aceptar</button>
          </div>
        </div>
      </form>
    </div>
  </div> 
</div>