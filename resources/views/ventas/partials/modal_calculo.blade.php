@component('components.modal', [ 'id' => 'modalCalculoPeso', 'size' => 'modal-md', 'title' => 'Calculo de Peso'])

  @slot('body')

    <div id="calculadoraData">

      <!-- Row0 -->
      <div class="row">

        <div class="col-md-6">
          <p> Peso Uni.: 
          <strong class="peso" data-value=""></strong> </p>
        </div>

        <div class="col-md-6">
          <p> Gravedad : <strong>7.85</strong> </p>
        </div>

      </div>
      <!-- Row0 -->

      <!-- Row1 -->
      <div class="row">

        <div class="col-md-4">
          <div class="input-group ">
            <span class="input-group-addon">Espesor</span>
            <input type="number" name="espesor" class="form-control input-calculate">
          </div>
        </div>

        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-addon">Ancho</span>
            <input type="number" name="ancho" class="form-control input-calculate">
          </div>      
        </div>

        <div class="col-md-4">
          <div class="input-group ">
            <span class="input-group-addon">Largo</span>
            <input type="number" name="alto" class="form-control input-calculate">
          </div>
        </div>

      </div>
      <!-- Row1 -->

      <!-- Row2 -->
      <div class="row mt-x10 mt-1">

        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-addon">Utilidad</span>
            <input type="number" min="0" max="100" name="utilidad" class="form-control input-calculate">
            <span class="input-group-addon">%</span>

          </div>      
        </div>

       <div class="col-md-6">
          <div class="input-group ">
            <span class="input-group-addon">Calculo</span>
            <input type="text" readonly name="calculo" class="form-control">
          </div>
        </div>        

      </div>
      <!-- Row2 -->

      <!-- Row4 -->
      <div class="row mt-x10 mt-1">

        <div class="col-md-6">
          <a href="#" id="aceptarCalculo" class="btn btn-primary btn-flat"> <span class="fa fa-check "></span> Aceptar </a>
        </div>

      </div>      
      <!-- Row4 -->

    </div>

  @endslot

@endcomponent