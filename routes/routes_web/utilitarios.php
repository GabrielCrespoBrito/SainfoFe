<?php

######################################## EMPRESA ########################################

Route::resource(
  'monitoreo/empresas',
  'Utilitario\EmpresaController', [ 'as' => 'monitoreo' ]
);

Route::get(
  'monitoreo/empresas_/search',
  'Utilitario\EmpresaController@search'
)->name('monitoreo.empresas.search');

######################################## DOCUMENTOS ########################################

# Ver documento de una empresa
Route::get(
  'monitoreo/empresa-documentos/{id?}',
  'Utilitario\EmpresaDocController@showDocs'
)->name('monitoreo.empresas.docs');


# Consultar documentos 
Route::get(
  'monitoreo/empresa-procesar-docs/{id?}',
  'Utilitario\EmpresaDocController@processDocs'
)->name('monitoreo.empresas.process_docs');


# Realizar consultar a la sunat
Route::post(
  'monitoreo/empresa-documentosempresas/{id?}/processdocs',
  'Utilitario\EmpresaDocController@processDocsStore'
)->name('monitoreo.empresas.process_docs_store');



# Vista para buscar los documentos
Route::get(
  'monitoreo/documentos/{id?}/buscar-documentos',
  'Utilitario\EmpresaDocController@search'
)->name('monitoreo.empresas.docs_search');



