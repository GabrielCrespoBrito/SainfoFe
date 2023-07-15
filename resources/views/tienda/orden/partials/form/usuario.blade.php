<div class='section-info usuario'>

  <div class='title-info'> <span class="fa fa-user"></span> Usuario </div>

  <div class="row">   

    <div class="form-group col-md-4">
      <label> Nombre Usuario  </label>
      <p class="form-control"> <a href="#"> {{ $data['username'] }}</a>  </p>
    </div>
    
    <div class="form-group col-md-4">
      <label> Nombre Mostrar  </label>
      <p class="form-control"> {{ $data['user_nicename'] }} </p>
    </div>

    <div class="form-group col-md-4">
      <label> Email  </label>
      <p class="form-control"> {{ $data['user_email'] }} </p>
    </div>

  </div>

</div>