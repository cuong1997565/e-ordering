<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_discounts_type = Permission::PERMISSION_LIST_DISCOUNTS_TYPE()->id;
            $permission_create_discount_type = Permission::PERMISSION_CREATE_DISCOUNT_TYPE()->id;
            $permission_edit_discount_type = Permission::PERMISSION_EDIT_DISCOUNT_TYPE()->id;
            $router->get('discount-types', ['uses' => 'DiscountTypeController@getAll', 'middleware' => "permission:{$permission_list_discounts_type}"]);
            $router->post('discount-type', ['uses' => 'DiscountTypeController@createDiscountType', 'middleware' => "permission:{$permission_create_discount_type}"]);
            $router->post('discount-type/{id}/update', ['uses' => 'DiscountTypeController@updateDiscountType', 'middleware' => "permission:{$permission_edit_discount_type}"]);

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
