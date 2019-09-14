<?php

use Illuminate\Http\Request;


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/cart', 'CartController@get')->name('cart_get');
    Route::post('/cart/{product}', 'CartController@add')->name('cart_add_product');
    Route::delete('/cart/{product}', 'CartController@remove')->name('cart_remove_product');
});
