<button 
data-title="{{ $model->CotNume }}"
data-id="{{ $model->CotNume }}"
data-edit="{{ $model->getRouteEdit()}}"
data-anular="{{ route('coti.anular' , $CotNume ) }}"
data-whatapp="{{ $model->getMessageLink() }}"
data-imprimir="{{ $model->getRouteImprimir() }}"
class="btn-xs btn-popover btn btn-primary data-info" 
data-url="#"> 
<span class="fa fa-eye"></span> 
</button>