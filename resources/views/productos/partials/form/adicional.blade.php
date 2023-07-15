<div class="row">
<div class="col-md-6"> 

  <div class="row">
    <p class="section_titulo"> Cuenta amarre contabilidad </p>
    <div class="form-group col-md-6">
      <label> Cuenta venta </label>
      <input name="cuenta_venta" min="0" required="required" class="form-control" type="number">
    </div>
    
    <div class="form-group col-md-6">
      <label> Cuenta compra </label>
      <input name="cuenta_compra" min="0" required="required" class="form-control" type="number">
    </div>            
  </div> 

  <!-- Nav  -->
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tabc_1" data-toggle="tab" aria-expanded="true">Descripción</a></li>
      <li class=""><a href="#tabc_2" data-toggle="tab" aria-expanded="false">Modo de uso</a></li>
      <li><a href="#tabc_3" data-toggle="tab">Ingredientes</a></li>  
    </ul>

    <div class="tab-content">
      
      <div class="tab-pane active" id="tabc_1">
        <textarea placeholder="Descripción" name="descripcion" class="form-control"></textarea>  
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="tabc_2">
        <textarea placeholder="Modo de uso" name="modo_uso" class="form-control"></textarea>
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="tabc_3">
        <textarea placeholder="Ingredientes" name="ingredientes" class="form-control"></textarea>
      </div>
      <!-- /.tab-pane -->

    </div>
    <!-- /.tab-content -->
  </div>
  <!-- /Nav  -->   

  <div class="row">
    <div class="form-group col-md-12">
      <label> Ultimo costo </label>
      <input name="ultimo_costo"  data-default="0" disabled="disabled" required="required" class="form-control" type="text">
    </div>               
  </div> 

  <div class="row">
    <div class="form-group col-md-12">
      <label> Cto. Prom </label>
      <input name="cto_prom"  data-default="0" disabled="disabled" required="required" class="form-control" type="text">
    </div>               
  </div> 


</div> 


<div class="col-md-6">
  <p class="section_titulo"> Stock Almacen </p> 
  <!-- inputs almacen -->
  <div class="row">
    <div class="form-group col-md-6">
      <label> Alma n° 1 </label>
      <input name="almacen_n1" data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>

  <div class="form-group col-md-6">
    <label> Alma n° 2 </label>
    <input name="almacen_n2"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
  </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6" >
      <label> Alma n° 3 </label>
      <input name="almacen_n3"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div> 

    <div class="form-group col-md-6" >
      <label> Alma n° 4 </label>
      <input name="almacen_n4"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label> Alma n° 5 </label>
      <input name="almacen_n5"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>

    <div class="form-group col-md-6">
      <label> Alma n° 6 </label>
      <input name="almacen_n6"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div> 
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label> Alma n° 7 </label>
      <input name="almacen_n7"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>

    <div class="form-group col-md-6">
      <label> Alma n° 8 </label>
      <input name="almacen_n8"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label> Alma n° 9 </label>
      <input name="almacen_n9"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div> 
    <div class="form-group col-md-6">
      <label> Alma n° 10 </label>
      <input name="almacen_n10"  data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>
  </div>

  <div class="row total-producto">
    <div class="form-group col-md-12">
      <label style="display:block; text-align: center"> Total </label>
      <input
      style="
          text-align: center;
          background-color: white;
          border: none;
          border-top: 1px solid black;
      "
       name="almacen_total" data-default="0" value="0" readonly="readonly" class="form-control" type="text">
    </div>
  </div>


  <!-- / inputs almacen -->

</div>  
</div>