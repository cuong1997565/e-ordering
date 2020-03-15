<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_product_types = Permission::PERMISSION_LIST_PRODUCT_TYPES()->id;
            $permission_create_product_type = Permission::PERMISSION_CREATE_PRODUCT_TYPE()->id;
            $permission_edit_product_type = Permission::PERMISSION_EDIT_PRODUCT_TYPE()->id;
            $router->get('product-types/', ['uses' => 'ProductTypeController@getAll', 'middleware' => "permission:{$permission_list_product_types}"]);
            $router->post('product-type/', ['uses' => 'ProductTypeController@createProductType', 'middleware' => "permission:{$permission_create_product_type}"]);
            $router->post('product-type/{id}', ['uses' => 'ProductTypeController@updateProductType', 'middleware' => "permission:{$permission_edit_product_type}"]);
            $router->get('product-type-about-product', ['uses' => 'ProductTypeController@getProductTypeAboutProduct']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('product-types/', ['uses' => 'ProductTypeController@getProductTypeAboutProduct']);

        });
    });
});
