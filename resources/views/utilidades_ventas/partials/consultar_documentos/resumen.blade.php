<div class="filtros resumen_consulta" style="display: none">

@include('components.block_elemento')

  <div class="filtro" id="condicion">
    <div class="cold-md-12">
      <fieldset class="fsStyle">      
        <legend class="legendStyle">Resumen</legend>

        <div class="row" id="demo">
          
          <?php $date = date('Y-m-d'); ?>           

          <div class="col-md-3 procesadas">
            <p class="form-control"> <span class="nombre"> Documentos procesados: </span> <span class="value"> </span>  </p>
          </div>

          <div class="col-md-3 encontradas">
            <p class="form-control"> <span class="nombre"> Sunat encontrados: </span> <span class="value"> </span>  </p>
          </div>          

          <div class="col-md-3 faltantes">
            <p class="form-control"> <span class="nombre"> Sunat faltantes: </span> <span class="value"> </span>  </p>
          </div>
        
          <div class="col-md-3 inexistente">
            <p class="form-control"> <span class="nombre"> Venta faltante: </span> <span class="value"> </span>  </p>
          </div>

        </div>                  

      </fieldset>
    </div>
  </div>
</div>