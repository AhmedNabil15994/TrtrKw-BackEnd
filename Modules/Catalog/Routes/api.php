<?php

Route::group(['prefix' => '/'], function () {

    Route::get('home', 'WebService\CatalogController@getHomeData')->name('api.home');

});

Route::group(['prefix' => 'catalog'], function () {

    Route::get('all-categories', 'WebService\CatalogController@getAllCategories')->name('api.categories.list');
    Route::get('products', 'WebService\CatalogController@getProductsByCategory')->name('api.products_by_category');
    Route::get('products/autocomplete', 'WebService\CatalogController@getAutoCompleteProducts')->name('api.get_autocomplete_products');
    Route::get('product/{id}/details', 'WebService\CatalogController@getProductDetails');
    Route::get('filter-values', 'WebService\CatalogController@getFilterValues');
    Route::get('offers', 'WebService\CatalogController@getCategoriesOffersData');
    Route::get('get-sub-categories/{id}', 'WebService\CatalogController@getSubCategoriesByCategoryId')->name('api.get_sub_categories');

});

