<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middlewae' => 'admin'], function () use ($router) {
            $router->get('price-list-item/{id}', ['uses' => 'PriceListItemController@getAll']);
            $router->post('price-list-item', ['uses' => 'PriceListItemController@createPriceListItem']);
            $router->post('price-list-item-delete',  ['uses' => 'PriceListItemController@deletePriceListItem']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
