    {{-- 


     <div class="row">

        <div class="form-group col-md-4">
          <label> Departamento </label>
          <select name="departamento" required="required" class="form-control">
            @foreach( $departamentos as $departamento )
              <option data-provincias="{{ $loop->first ? $departamento->provincias : '' }}" data-distritos="{{ $loop->first ? $departamento->distritos : '' }}" value="{{ $departamento->depcodi }}"> {{ $departamento->depnomb }}</option>
            @endforeach
          </select>
        </div>


        <div class="form-group col-md-4">
          <label> Provincia </label>
          <select name="provincia"  required="required" class="form-control">
            @foreach( $departamentos->first()->provincias as $provincia )
              <option value="{{ $provincia->provcodi }}"> {{ $provincia->provnomb }}</option>
            @endforeach
          </select>
        </div>

        <!-- Distritos -->
        <div class="form-group col-md-4">
          <label> Distrito </label>
          <select name="distrito"  required="required" class="form-control">
          @foreach( $departamentos->first()->provincias->first()->distritos as $distrito )
          <option value="{{ $distrito->ubicodi }}"> {{ $distrito->ubinomb }}</option>
          @endforeach
          </select>
        </div>

      </div>

      --}}      


      {{-- 
     <div class="row">

        <div class="form-group col-md-4">
          <label> Ubigeo </label>
          <input data-defalt="{{ $departamentos->first()->provincias->first()->distritos->first()->ubicodi }}" name="ubigeo" value="{{ $departamentos->first()->provincias->first()->distritos->first()->ubicodi }}" readonly="readonly" required="required" class="form-control" type="text">
        </div>
        
      </div>
      --}}      
