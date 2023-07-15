<li class="treeview">
  <a href="#">
    <i class="fa fa-money"></i> <span>Caja</span>
    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
  </a>
  
  <ul class="treeview-menu">
    <li> <a href="{{ route('cajas.index') }}">  <i class="fa fa-circle-o"></i> Ver   </a> </li>
    <li> <a href="{{ route('cajas.cuentas', 'por_cobrar' ) }}"> <i class="fa fa-circle-o"></i> Cuentas Por Cobrar </a></li>
    <li> <a href="{{ route('cajas.cuentas' , 'por_pagar') }}"> <i class="fa fa-circle-o"></i> Cuentas Por Pagar </a></li>
  </ul> 

</li> 