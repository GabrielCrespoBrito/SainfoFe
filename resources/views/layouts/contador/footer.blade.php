</div> 

{{-- end body --}}
@include("partials.modal_elegir_empresa")
<!-- jQuery 3 -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('js/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<!-- Toasts -->
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script type="text/javascript">
	window.url_datatable_es = "{{ asset('plugins/datatable/es_lang.json') }}";
</script>

@yield('js')
@stack('js')

@if( session()->has('notificacion') )

<script type="text/javascript">
	$(function(){
    
	  $.toast({
	    heading   : "{{ session()->get('titulo')  }}" ,
	    text      : "{{ session()->get('mensaje') }}",
	    position  : 'top-center',
	    showHideTransition : "showHideTransition", 
	    hideAfter : 3000,
	    icon      : "{{ session()->get('tipo') }}",
	  });
	});
</script>

@endif

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
</body>
</html>