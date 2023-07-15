<div class="pl-x10 part-user">
    <label for="form-usuarios"> Usuarios </label>

    <div class="row">
        <!--  -->
        <div class="form-group col-md-12">
        @foreach( $usuarios as $usuario )

        @php
        $isSelected = $usuario->isLocalSelected($model->LocCodi);
        @endphp

        @if( $usuario->isAdmin() or $usuario->isOwner() )
        @continue
        @endif
        
        <div class="checkbox">
        <label class="{{ $isSelected ? 'c-green' : 'c-gray' }}"> <input name="users[]" {{ $isSelected ? 'checked' : '' }} value="{{ $usuario->usucodi }}" type="checkbox"> <span class="user-name-label"> {{ $usuario->usulogi }} </span> </label>

        </div>

        @endforeach
        </div>
        <!--  -->
    </div>
</div>