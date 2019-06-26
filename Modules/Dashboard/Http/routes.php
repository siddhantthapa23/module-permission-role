<?php

Route::group(['middleware' => 'web', 'prefix' => 'dashboard', 'namespace' => 'Modules\Dashboard\Http\Controllers'], function()
{
    Route::group(['middleware' => 'auth'], function() {
        Route::get('/', 'DashboardController@index')->name('dashboard');
    });
});
