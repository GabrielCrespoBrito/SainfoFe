<div class="mt-x10">
<div class="btn-group">
  
  <button type="button"  data-url="{{ route('admin.guias.delete_pdf', [ 'id' => $model->GuiOper, 'create' => '0' ] )}}"  
  class="btn btn-default btn-flat eliminar-documento-pdf"> <span class="fa fa-file-text-o"></span> Eliminar PDF  </button>

  <button type="button"  data-url="{{ route('admin.guias.delete_pdf', [ 'id'=> $model->GuiOper, 'create' => '1' ] )}}"  
  class="btn btn-default btn-flat eliminar-documento-pdf"> <span class="fa fa-file-text-o"></span> Crear Nuevo PDF  </button>

</div>
</div>
