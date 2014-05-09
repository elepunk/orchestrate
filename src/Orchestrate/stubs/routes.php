<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

Route::get('{{lower_name}}', function()
{
    return View::make('{{lower_vendor}}/{{lower_name}}::hello');
});