<div class='section-info cliente'>

  <div class='title-info'> <span class="fa fa-bookmark-o"></span> Cliente </div>

  <div class="row">   
    <div class="form-group col-md-4">
      <label> Razon Social  </label>
      <p class="form-control">  {{ $data['razon_social'] }}   </p>
    </div>
    
    <div class="form-group col-md-3">
      <label> Ruc/DNI  </label>
      <p class="form-control">  {{ $data['documento'] }} </p>
    </div>

    <div class="form-group col-md-3">
      <label> Email  </label>
      <p class="form-control">  {{ $data['email'] }} </p>
    </div>

    <div class="form-group col-md-2">
      <label> Telefono  </label>
      <p class="form-control"> {{ $data['telefono'] }} </p>
    </div>
  </div>

  <div class="row">   
    <div class="form-group col-md-12">
      <label> Mensaje  </label>
      <textarea class="form-control" readonly> {{ $data['mensaje'] }} </textarea>
    </div>
    
  </div>

</div>