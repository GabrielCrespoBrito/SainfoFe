@include('guia_remision.partials.edit' , [ 
  'entidad' => 'Proveedor', 
  'titulo' => 'Guia de Ingreso', 
  'routeSearch' => route('guia_ingreso.search'),
  'routeCreate' => route('guia_ingreso.create'),
  'routeIndex' => route('guia_ingreso.index'),
  'routeUpdate' => route('guia_ingreso.update' , $guia->GuiOper ),
  'routeDespacho' => route('guia_ingreso.despacho' ,  $guia->GuiOper  ),
])