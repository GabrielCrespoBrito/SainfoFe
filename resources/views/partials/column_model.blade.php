@php
	$tableName = $model->getTable();
	// $links = [];

	switch($tableName){
		case 'monitor_empresas': 
			$links = [
				['src' => route('monitoreo.empresas.show', $model->id ) , 'texto' => 'Ver'],
				['src' => '#' , 'texto' => 'Eliminar' , 'id' => $model->id, 'class' => 'eliminate-element' ],
				['src' => route('monitoreo.empresas.process_docs', $model->id ) , 'texto' => 'Procesar Documentos'],
				['src' => route('monitoreo.empresas.docs', $model->id ) , 'texto' => 'Documentos'],
	];
		break;
	}
@endphp

@include('partials.column_accion', ['links' => $links ])




