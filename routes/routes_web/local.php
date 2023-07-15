<?php

use Illuminate\Support\Facades\Route;

Route::resource('locales', 'LocalController')
->except('show');