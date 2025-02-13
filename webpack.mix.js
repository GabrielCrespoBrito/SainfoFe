let mix = require('laravel-mix');

mix.webpackConfig({
  stats: {
    children: true,
  },
});

{/* <script type="text/javascript" src="{{ asset(mix('mix/print_js/ConectorPlugin.js'))}}"></script> */}
{/* <script type="text/javascript" src="{{ asset(mix('mix/ventas/print_ticket.js'))}}"></script> */}
// { { -- < script type = "text/javascript" src = "{{ asset(mix('js/print_js/ConectorPlugin.js'))}}" ></script > --} }
// { { -- < script type = "text/javascript" src = "{{ asset(mix('js/ventas/print_ticket.js'))}}" ></script > --} }

mix
  .js('public/js/print_js/ConectorPlugin.js', 'public/js/mix/')
  .js('public/js/ventas/print_ticket.js', 'public/js/mix/')
  .js('public/js/helpers.js', 'public/js/mix/')
  .js('public/js/ventas/scripts.js', 'public/js/ventas/mix/')
  .js('public/js/dashboard.js', 'public/js/mix/')
  .js('public/js/ventas/index.js', 'public/js/ventas/mix/')
  .js('public/js/productos/index.js', 'public/js/productos/mix/')
  .js('public/js/ventas/canje.js', 'public/js/ventas/mix/')
  .js('public/js/clientes/scripts.js', 'public/js/clientes/mix/')
  .js('public/js/cajas/pagos.js', 'public/js/cajas/mix/')
  .js('public/js/ecommerce/scripts.js', 'public/js/ecommerce/mix/')
  .js('public/js/guia/index.js', 'public/js/guia/mix/')  
  .js('public/js/guia/scripts.js', 'public/js/guia/mix/')
  .js('public/js/compras/crud.js', 'public/js/compras/mix/')
  .js('public/js/cotizaciones/crear_modificar.js', 'public/js/cotizaciones/mix/')
  .js('public/js/cotizaciones/index.js', 'public/js/cotizaciones/mix/')
  .js('public/js/sainfo.js', 'public/js/mix/sainfo.js')
  .js('public/js/admin.js', 'public/js/mix/admin.js')
  .js('public/js/unidad/index.js', 'public/js/unidad/mix/index.js')
  .js('public/js/admin/documentos.js', 'public/js/admin/mix/documentos.js')
  .js('public/js/admin/tipo_cambio.js', 'public/js/admin/mix/tipo_cambio.js')
  .js('public/js/reportes/kardex_fisico.js', 'public/js/reportes/mix/kardex_fisico.js')
  .js('public/js/reportes/reporte.js', 'public/js/mix/')
  .js('public/js/admin/notificaciones.js', 'public/js/admin/mix/notificaciones.js')
  .css('public/page/css/style.css', 'public/page/css/mix/')
  .styles([
    'public/css/style.css',
    'public/css/AdminLTE.css',
    'public/css/all-skins.css'
  ], 'public/css/all.css')
  .sass('public/css/admin.scss', 'public/css/admin_styles.css')
  .version();

mix.js('resources/js/app.js', 'public/js')
  .react();
  // .postCss('resources/css/app.css', 'public/css', [
  // ]);