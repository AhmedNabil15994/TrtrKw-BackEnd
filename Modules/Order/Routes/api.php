<?php

Route::post('webhooks', 'WebService\OrderController@webhooks')->name('api.orders.webhooks');

Route::group(['prefix' => 'orders'], function () {

    Route::post('create', 'WebService\OrderController@createOrder')->name('api.orders.create');

    Route::get('success', 'WebService\OrderController@success')
        ->name('api.orders.success');

    Route::get('failed', 'WebService\OrderController@failed')
        ->name('api.orders.failed');


    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

        Route::get('list', 'WebService\OrderController@userOrdersList')->name('api.orders.index');
        Route::get('{id}/details', 'WebService\OrderController@getOrderDetails')->name('api.orders.details');

    });

});
