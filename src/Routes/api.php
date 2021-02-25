<?php

Route::namespace('Yan9\Etocdn\Controllers')->as('etocdn::')->middleware('api')->group(function () {
    // Routes defined here have the api middleware applied
    // like the api.php file in a laravel project
    // They also have an applied controller namespace and a route names.
});
Route::post('/uploadExample','Yan9\Etocdn\Controllers\UploadExampleController@index');
