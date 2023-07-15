@php
$id = $model->usucodi . "-" . $model->loccodi;
@endphp
@include('partials.column_accion', [
'links' => [
['texto'=>'Editar' , 'src'=> route('admin.user-local.edit',['usucodi'=>$model->usucodi,'loccodi'=>$model->loccodi])],
['texto'=>'Eliminar','src'=>"#", 'id' => $id, 'class' => 'eliminate-element' ]] ] ) </td>
