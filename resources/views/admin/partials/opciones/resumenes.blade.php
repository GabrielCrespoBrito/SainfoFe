<div class="opciones-entidad">
  <div class="seccion-titulo"> {{ $model->DocNume }} </div>
  <div class="seccion">

  <hr>

    <div class="opcion-btn text-center">

      <label style="border-bottom:1px dashed #999" title="Validar Por el Estado Del Documento del Resumen"> <input checked type="radio" name="tipo_validacion" value="estado"> Por Estado </label>
      <label style="border-bottom:1px dashed #999" title="Validar Internamente" class="ml-x10"> <input type="radio" name="tipo_validacion" value="directo"> Directo </label>


      <a href="#" data-url="{{ route('admin.resumenes.validar' , ['numoper' => $model->NumOper, 'docnume' => $model->DocNume ]) }}" class="btn validar-resumen btn-flat pull-right btn-default btn-block"> <span class="fa fa-check"> </span> Validar </a>

    </div>
  </div>
</div>