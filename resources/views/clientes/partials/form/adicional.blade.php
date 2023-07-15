      <div class="row">
        <div class="form-group col-md-12">
          <label> Contacto </label>
          <input name="contacto" class="form-control" type="text">
        </div>        
      </div>


     <div class="row">

        <div class="form-group col-md-12">          

          <label> Vendedor </label>
          <select name="vendedor" class="form-control">

              @php
              $vendedores = isset($vendedores) ? $vendedores : get_empresa()->vendedores;
              @endphp

            @foreach( $vendedores as $vendedor )
              <option value="{{ $vendedor->Vencodi }}"> {{ $vendedor->vennomb }}</option>
            @endforeach
          </select>

        </div> 
                
      </div>
    

     <div class="row">

        <div class="form-group col-md-6">
          <label> Moneda </label>   
          @php
            $monedas = isset($monedas) ? $monedas : App\Moneda::all();
          @endphp          
          <select name="moneda" class="form-control">
            @foreach( $monedas as $moneda )
              <option value="{{ $moneda->moncodi }}"> {{ $moneda->monnomb }}</option>
            @endforeach
          </select>                 
        </div>

        <div class="form-group col-md-6">
          <label> Linea de credito </label>
          <input name="linea_credito" readonly="readonly" class="form-control" type="text">
        </div>

     </div>

     <div class="row">

        <div class="form-group col-md-6">
          <label> Lista de precio </label>
          @php
            $lista_precios = isset($lista_precios) ? $lista_precios : App\ListaPrecio::all();
          @endphp  
          <select name="lista_precio" class="form-control">
            @foreach( $lista_precios as $lista_precio )
              <option value="{{ $lista_precio->LisCodi }}"> {{ $lista_precio->LisNomb }}</option>
            @endforeach            
          </select>          
        </div>

        <div class="form-group col-md-6">
          <label> Afecto Perc </label>
          <input name="af_pe" class="form-control" type="text">
        </div>

     </div>
