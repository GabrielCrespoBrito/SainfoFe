<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="fa fa-user fa-1x"></span> <span class="hidden-xs"> {{ auth()->user()->usulogi }} </span>
  </a>
  <ul class="dropdown-menu">
    <li class="">
      <div>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf </form>
        <a href="#" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();" class="btn btn-default btn-block btn-flat">Salir</a>
      </div>
    </li>
  </ul>
</li>