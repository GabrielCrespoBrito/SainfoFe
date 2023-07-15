@if($import)
@if( $importInfo['messages'] )

<div class="botones">
	<div class="col-md-12 col-lg-12 col-sm-12 no_pr">
    
    @component('components.messages.alert',['title'])
      @slot('message')
        @foreach ($importInfo['messages'] as $message)
        <div> - {{ $message }}  </div>
        @endforeach
      @endslot
    
    @endcomponent

  </div>
</div>

@endif
@endif