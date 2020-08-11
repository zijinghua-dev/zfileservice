<?php

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::group(['prefix' => 'file',], function () {
        Route::post('/', 'FileController@upload');
        Route::get('/{uuid}', 'FileController@show');
    });
});