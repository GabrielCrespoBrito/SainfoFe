<div class="modal modal-seleccion fade" id="modalMail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Enviar Correo</h4>
      </div>
      <div class="modal-body">
        <div class="botones_div">

        <a class="btn pull-left btn-success btn-flat send_correo">
          <span class="fa fa-envelope"> </span> Enviar</a>

        </div>        

        <div class="correo_send">  

          <div class="form-group">
            <div class="input-group date">
              <div class="input-group-addon">Para </div>
              <input class="form-control pull-right corre_hasta" id="tags" name="corre_hasta" type="text">
            </div>
          </div>

          <div class="form-group">
            <div class="input-group date">
              <div class="input-group-addon">Asunto </div>
              <input data-default="DOCUMENTO TRIBUTARIO ELECTRÓNICO" class="form-control pull-right corre_asunto" value="COTIZACIÓN" name="corre_asunto" type="text">
            </div>
          </div>          

          <div class="form-group">
            <label> Mensaje </label>
            <textarea data-default="" class="form-control pull-right corre_mensaje" name="corre_mensaje"> </textarea>
          </div>        
        </div>

      </div>
    </div>

    </div>
    <!-- /.modal-content -->
 </div>
  <!-- /.modal-dialog -->


@push('js')

@endpush