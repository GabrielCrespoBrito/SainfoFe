<?php

Route::get('export/excell', 'Export\ExportExcellController@show')->name('export.excell');
Route::get('export/excell/generate', 'Export\ExportExcellController@generate')->name('export.excell.generate');
