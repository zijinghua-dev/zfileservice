<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'file',], function () {
        Route::post('/', 'FileController@upload');
        Route::get('/{md5?}', 'FileController@show');
        Route::delete('/{md5}', 'FileController@delete');
    });
    Route::group(['prefix' => 'config',], function () {
        Route::post('/', 'ConfigController@index');
    });
});
