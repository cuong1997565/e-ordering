<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_brands = Permission::PERMISSION_LIST_BRANDS()->id;
//            $permission_create_brand = Permission::PERMISSION_CREATE_BRAND()->id;
//            $permission_edit_brand = Permission::PERMISSION_EDIT_BRAND()->id;
//            $permission_delete_brand = Permission::PERMISSION_DELETE_BRAND()->id;
            $router->post('brands', ['uses' => 'BrandsController@createBrand']);
            $router->post('brands/{brand_id}', ['uses' => 'BrandsController@updateBrand']);
            $router->get('brands/search', ['uses' => 'BrandsController@searchBrandsByName']);
            $router->get('brands', ['uses' => 'BrandsController@index']);
            $router->post('brands/{brand_id}/delete', ['uses' => 'BrandsController@delete']);
            $router->get('brands/name/{brand_name}', ['uses' => 'BrandsController@getBrandByName']);
            $router->get('brand_products', ['uses' => 'BrandsController@brandProduct']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('brands', ['uses' => 'BrandsController@getClientBrands']);
        });
    });
});
