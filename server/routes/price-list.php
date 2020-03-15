<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_prices = Permission::PERMISSION_LIST_PRICES()->id;
            $permission_create_price = Permission::PERMISSION_CREATE_PRICE()->id;
            $permission_edit_price = Permission::PERMISSION_EDIT_PRICE()->id;
            $permission_delete_price = Permission::PERMISSION_DELETE_PRICE()->id;
            $router->get('price-lists/', ['uses' => 'PriceListController@getAll', 'middleware' => "permission:{$permission_list_prices}"]);
            $router->post('price-list/', ['uses' => 'PriceListController@createPriceList', 'middleware' => "permission:{$permission_create_price}"]);
            $router->post('price-list/{id}', ['uses' => 'PriceListController@updatePriceList']);
            $router->get('price-list-items/',  ['uses' => 'PriceListController@getSomeFieldPriceList']);
            $router->get('price-list/{id}', ['uses' => 'PriceListController@index', 'middleware' => "permission:{$permission_edit_price}"]);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
