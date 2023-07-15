<div class="modal modal-seleccion fade" id="modalImportacion">
<div class="modal-dialog modal-sm">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Importar documento</h4>
    </div>
    <div class="modal-body" style="overflow: hidden;">        
      <div class="botones_div">
        <a class="btn pull-left btn-success btn-flat aceptar_importacion">
          <span class="fa fa-check"> </span> Aceptar</a>
        <a class="btn pull-left btn-flat btn-danger btn-flat salir" data-dismiss="modal"> Salir </a>
      </div>        

      <div class="importacion">
        <div class="form-group col-md-12 no_pl">  
          <div class="row">   
            <div class="col-md-4 no_pr">                 
              <input class="form-control input-sm text-uppercase" placeholder="Serie" value="{{ $notaCredito->serie }}" name="serie_doc">     
            </div>
            <div class="col-md-8 no_pl">                                     
              <input class="form-control input-sm" placeholder="N° Documento" name="num_doc" value="{{ $notaCredito->numero }}" >
            </div>
          </div>
        </div>

        <div class="div_list pull-left">
          <a href="#" class="lista-coti btn btn-default btn-block"> <span class="fa fa-book"></span> Lista cotizaciones</a>
        </div>
      </div>
    </div>
  </div>

  </div>
</div>

