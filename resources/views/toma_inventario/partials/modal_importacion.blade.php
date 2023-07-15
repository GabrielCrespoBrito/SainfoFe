@component('components.modal', ['id' => 'modalImport' , 'size' => 'modal-sm' , 'title' => "Importacion" ])
  @slot('body')
    <div 
    data-url="{{route('toma_inventario.export_excell')}}"
    data-urlSend="{{route('toma_inventario.import_excell')}}"
    id="root-toma-inventario">
    </div>
  @endslot
@endcomponent