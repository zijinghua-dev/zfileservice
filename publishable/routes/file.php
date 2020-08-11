<?php

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'file',], function () {
        Route::post('/', 'FileController@upload');
        Route::get('/{uuid}', 'FileController@show');
    });
});