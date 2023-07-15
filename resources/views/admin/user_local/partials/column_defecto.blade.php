@if( $model->isDefault() )
<a class="btn btn-primary disabled btn-xs" href="#"> <span class="fa fa-check"></span></a>
@else
<a class="btn btn-default btn-xs" href="{{ route('admin.user-local.default' , ['usucodi' => $model->usucodi , 'loccodi' =>  $model->loccodi] ) }}"> <span class="fa fa-square-o"></span></a>
@endif
