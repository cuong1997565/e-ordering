<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_so_orders = Permission::PERMISSION_LIST_ALL_SO_ORDER()->id;
            $permission_create_so_order = Permission::PERMISSION_CREATE_SO_ORDER()->id;
            $permission_edit_so_order = Permission::PERMISSION_EDIT_SO_ORDER()->id;
            $permission_delete_so_order = Permission::PERMISSION_DELETE_SO_ORDER()->id;
            $router->get('sale_orders/{sale_order_id}', ['uses' => 'SaleOrderController@getSaleOrderAdminByID', 'middleware' => "permission:{$permission_edit_so_order}"]);

            $router->get('sale_orders/', ['uses' => 'SaleOrderController@getSaleOrderAdmin', 'middleware' => "permission:{$permission_list_so_orders}"]);

            $router->post('sale_orders/', ['uses' => 'SaleOrderController@createSaleOrderAdmin', 'middleware' => "permission:{$permission_create_so_order}"]);

            $router->post('sale_orders/confirm/{sale_id}', ['uses' => 'SaleOrderController@updateSaleOrderConfirmAdmin']);

            $router->post('sale_orders/submit/{sale_id}' , ['uses' => 'SaleOrderController@updateSaleOrderSubmitAdmin']);

            $router->get('get_order_about_sale_order/{order_id}',  ['uses' => 'SaleOrderController@getOrderAboutSaleOrder']);

            $router->get('check_remaining_quantity_and_status_close_about_sale_order/{sale_id}',  ['uses' => 'SaleOrderController@checkRemainingQuantityAndStatusSaleOrder']);

            $router->get('check_remaining_quantity_sale_order/{sale_id}',  ['uses' => 'SaleOrderController@checkRemainingQuantitySaleOrder']);

            $router->post('sale/{sale_id}/close', ['uses' => 'SaleOrderController@closeSaleOrderByAdmin']);

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->get('sale_orders/{sale_order_id}', ['uses' => 'SaleOrderController@getSaleOrderFrontendByID']);

        $router->get('get_order_about_sale_order/{order_id}',  ['uses' => 'SaleOrderController@getOrderAboutSaleOrder']);

        $router->get('count_sale_order_about_delivery_note/{sale_order_id}', ['uses' => 'SaleOrderController@countSaleOrderAboutDeliveryNote']);
    });
});
