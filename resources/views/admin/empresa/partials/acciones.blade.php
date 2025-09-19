@if( $empresa->isWeb() )
@include('admin.empresa.partials.acciones.eliminar')
@include('admin.empresa.partials.acciones.limpiar')
@include('admin.empresa.partials.acciones.cambiar_estado')
@include('admin.empresa.partials.acciones.igv_cambio')
@include('admin.empresa.partials.acciones.update_costos_reales')
@include('admin.empresa.partials.acciones.enviar_email_venc_suscripcion')
@include('admin.empresa.partials.acciones.enviar_email_porvenc_suscripcion')
@include('admin.empresa.partials.acciones.importar_xmls')
@else
@include('admin.empresa.partials.acciones.cambiar_estado')
@include('admin.empresa.partials.acciones.enviar_email_venc_suscripcion')
@include('admin.empresa.partials.acciones.enviar_email_porvenc_suscripcion')
@endif


