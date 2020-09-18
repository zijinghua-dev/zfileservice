<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'file',], function () {
        Route::post('/store', 'FileController@upload');
        Route::post('/show', 'FileController@show');
        Route::post('/delete', 'FileController@delete');
    });
    Route::group(['prefix' => 'config',], function () {
        Route::post('/', 'ConfigController@index');
    });
});
