<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router)
{
    $router->post('product/check_barcode', ['uses' =>'ProductsController@barcodeCheck']);
    $router->post('product/list_check_barcode', ['uses' =>'ProductsController@listBarcodeCheck']);

    $router->group(['middleware' => 'auth'], function () use ($router){

        $router->group(['middleware' => 'admin'], function () use ($router){
            $permission_list_products = Permission::PERMISSION_LIST_PRODUCTS()->id;
            $permission_create_product = Permission::PERMISSION_CREATE_PRODUCT()->id;
            $permission_edit_product = Permission::PERMISSION_EDIT_PRODUCT()->id;
//            $permission_delete_product = Permission::PERMISSION_DELETE_PRODUCT()->id;
            $router->get('products', ['uses' => 'ProductsController@getAll', 'middleware' => "permission:{$permission_list_products}"]);
            $router->post('products', ['uses' => 'ProductsController@createProduct', 'middleware' => "permission:{$permission_create_product}"]);
            $router->post('products/{product_id}', ['uses' => 'ProductsController@updateProduct', 'middleware' => "permission:{$permission_edit_product}"]);
            $router->post('products/{product_id}/delete', ['uses' =>'ProductsController@delete']);
            $router->get('product/detail' , ['uses' => 'ProductsController@getProductDetail']);
            $router->get('factories/{factory_id}/products/search', ['uses' => 'ProductsController@searchProductsForFactory']);
            $router->get('products/{product_id}/detail/{factory_id}', ['uses' =>'ProductsController@getDetail']);

        });

        $router->group(['middleware' => 'member'], function () use ($router){

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('product_client', ['uses' => 'ProductsController@getClientProducts']);
            $router->get('factories/{factory_id}/products/search', ['uses' => 'ProductsController@searchProductsForFactory']);
            $router->post('products/{product_id}/checkAmount', ['uses' => 'ProductsController@checkAmountProduct']);
        });
        $router->get('product_client', ['uses' => 'ProductsController@getClientProducts']);
        $router->get('products', ['uses' => 'ProductsController@getClientSreachProducts']);
        $router->get('products/{product_id}/detail/{factory_id}', ['uses' =>'ProductsController@getDetail']);
    });
});
