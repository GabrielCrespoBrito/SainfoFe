<div class="">
  <a href="#" class="btn btn-block btn-default btn-flat"> Consultar Estado </a> 
</div>


{{-- <div class="mt-x10">
  <a 
    data-url="{{ route('admin.documentos.delete_pdf', $model->VtaOper) }}"  
    href="#" 
    class="btn  btn-default btn-flat eliminar-documento-pdf">
    <span class="fa fa-trash"></span> Eliminar PDF 
  </a> 
  <input type="checkbox" name="re_created" value="1">
</div> --}}
{{-- ---- --}}

  <div class="mt-x10">
  <div class="btn-group">
    
    <button type="button"  data-url="{{ route('admin.documentos.delete_pdf', ['id'=>$model->VtaOper,'create'=>'0'] )}}"  
    class="btn btn-default btn-flat eliminar-documento-pdf"> <span class="fa fa-file-text-o"></span> Eliminar PDF  </button>

    <button type="button"  data-url="{{ route('admin.documentos.delete_pdf', ['id'=>$model->VtaOper,'create'=>'1'] )}}"  
    class="btn btn-default btn-flat eliminar-documento-pdf"> <span class="fa fa-file-text-o"></span> Crear Nuevo PDF  </button>


  </div>
  </div>

{{-- --- --}}

{{-- loremp-ipsum-odlor-loremp-ipsum-odlor-loremp-ipsum-odlor --}}
{{-- loremp-ipsum-odlor-loremp-ipsum-odlor-loremp-ipsum-odlor --}}
{{-- loremp-ipsum-odlor-loremp-ipsum-odlor-loremp-ipsum-odlor --}}
{{-- loremp-ipsum-odlor-loremp-ipsum-odlor-loremp-ipsum-odlor --}}

<div class="mt-x10">
  <a 
    data-url="{{ route('admin.documentos.delete', $model->VtaOper) }}"
    href="#"
    class="eliminar-documento btn btn-block btn-sm btn-danger btn-flat"> Eliminar 
  </a>  
</div>

<p>
  <input 
    type="password" 
    name="password_admin"
    id="random"
    autocomplete="off"
    class="mt-x2 form-control input-sm text-center"
    placeholder="Contraseña">
</p>