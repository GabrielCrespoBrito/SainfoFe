@component('components.modal', ['id'=> 'modalImportarVenta' , 'title' => 'Importar venta' ])
  
  @slot('body')

    @component('components.table' , ['thead' => ['N°Oper','T.Doc' , 'N° Doc' , 'Fecha E', 'Ruc' , 'Razón Social' , 'Moneda' , 'Importe' , 'Responsable' , 'Importar' ] ])
      
      @slot('body')
      @php
        $ventas = App\Venta::with(['cliente_with' => function($q){
          $q->where('EmpCodi', empcodi());
        } , 'moneda'])
        ->where('EmpCodi', empcodi())
        ->where('VtaSdCa' , '>' , "0")
        ->get();        
      @endphp 

      {{-- @dd( $ventas ) --}}

        @foreach( $ventas as $venta )
          <tr>
            <td> {{ $venta->VtaOper }} </td>            
            <td> {{ $venta->TidCodi }} </td>            
            <td> {{ $venta->VtaNume }} </td>            
            <td> {{ $venta->VtaFvta }} </td>            
            <td> {{ $venta->cliente_with->PCRucc }} </td>            
            <td> {{ $venta->cliente_with->PCNomb }} </td>            
            <td> {{ $venta->moneda->monnomb }} </td>
            <td> {{ $venta->VtaImpo }} </td>
            <td> {{ $venta->Vencodi }} </td>
            <td> <a href="{{ route('guia.create' , $venta->VtaOper ) }}" class="btn btn-xs btn-default"> Importar </a> </td>
          </tr>
        @endforeach
      @endslot

    @endcomponent

  @endslot

@endcomponent