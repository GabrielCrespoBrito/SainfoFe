@php 
	$asunto = isset($asunto) ? $asunto : '';
	$title = isset($title) ? $title : 'Enviar correo';	
@endphp

@component('components.modal', ['id' => 'modalEnvioCorreo' , 'size' => 'modal-lg' , 'title' => $title ])

  @slot('body')

		<div class="correo_send">  

		  <div class="form-group">
		    <div class="input-group">
		      <div class="input-group-addon">Para </div>
		      <input data-field="PCCMail" class="form-control pull-right" name="to" type="text">
		    </div>
		  </div>

		  <div class="form-group">
		    <div class="input-group date">
		      <div class="input-group-addon">Asunto </div>
		      <input data-default="{{ $asunto }}" class="form-control" value="{{ $asunto }}" name="subject" type="text">
		    </div>
		  </div>          

		  <div class="form-group">
		    <label> Mensaje </label>
		    <textarea class="form-control pull-right" name="message"> </textarea>
		  </div>
		</div>

  @endslot 

  @slot('footer')

  		<div class="botones_div">
			<a data-url="{{ $url }}" data-adicioonal_info="" class="btn pull-left btn-success btn-flat send_correo">
		  	<span class="fa fa-envelope"> </span> Enviar</a>
		</div> 
  @endslot
@endcomponent 