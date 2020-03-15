<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_read_po_order = Permission::PERMISSION_READ_PO_ORDER()->id;
            $permission_list_orders = Permission::PERMISSION_LIST_ORDERS()->id;
            $permission_create_order = Permission::PERMISSION_CREATE_ORDER()->id;
            $permission_edit_order = Permission::PERMISSION_EDIT_ORDER()->id;
            $permission_delete_order = Permission::PERMISSION_DELETE_ORDER()->id;
            $permission_reject_item_product_order = Permission::PERMISSION_EDIT_ORDER()->id;
            $permission_update_order = Permission::PERMISSION_UPDATE_ORDER()->id;
            $permission_approved_order = Permission::PERMISSION_APPROVE_ORDER()->id;
            $permission_review_order = Permission::PERMISSION_REVIEW_ORDER()->id;
            $permission_reject_order = Permission::PERMISSION_REJECT_ORDER()->id;

            $router->get('orders', ['uses' => 'OrdersController@getAllCustomerOrdersForAdmin', 'middleware' => "permission:{$permission_list_orders}"]);

            $router->get('orders/detail', ['uses' => 'OrdersController@getOrderDetailAdmin', 'middleware' => "permission:{$permission_edit_order}"]);

            $router->get('order_sreach', ['uses' => 'OrdersController@getClientSreachOrders']);

            $router->post('orders/{order_id}', ['uses' => 'OrdersController@updateOrder', 'middleware' => "permission:{$permission_edit_order}"]);
//            $router->post('orders/update/order_product', ['uses' => 'OrdersController@updateOrderProduct']);

            $router->post('orders/{order_id}/update', ['uses' => 'OrdersController@updateOrderAdmin',  'middleware' => "permission:{$permission_update_order}"]);

            $router->post('orders/{order_id}/review', ['uses' => 'OrdersController@reviewCustomerOrderByAdmin', 'middleware' => "permission:{$permission_review_order}"]);

            $router->post('orders/{order_id}/approve', ['uses' => 'OrdersController@approveCustomerOrderByAdmin',  'middleware' => "permission:{$permission_approved_order}"]);

            $router->post('orders/{order_id}/reject', ['uses' => 'OrdersController@rejectCustomerOrderByAdmin',  'middleware' => "permission:{$permission_reject_order}"]);

            $router->post('orders', ['uses' => 'OrdersController@createOrders', 'middleware' => "permission:{$permission_create_order}"]);
//            $router->post('orders/detail/change_status', ['uses' => 'OrdersController@ChangeStatusAdmin']);

            $router->post('orders/delete/order_product', ['uses' => 'OrdersController@deleteOrderProduct', 'middleware' => "permission:{$permission_delete_order}"]);

            $router->post('order-product/{order_id}/change/status', ['uses' => 'OrdersController@changeStatusProductOrder',  'middleware' => "permission:{$permission_reject_item_product_order}"]);

            $router->post('order/{order_id}/close',  ['uses' => 'OrdersController@closeOrderByAdmin']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('orders', ['uses' => 'OrdersController@getClientSreachOrders']);
            $router->get('orders/{order_id}', ['uses' => 'OrdersController@getClientOrder']);
            $router->get('order_sreach', ['uses' => 'OrdersController@getClientSreachOrders']);
            $router->post('orders/add', ['uses' => 'OrdersController@createOrderClient']);
            $router->post('orders/draft', ['uses' => 'OrdersController@createDraftOrder']);
            $router->post('orders/{order_id}/draf/update', ['uses' => 'OrdersController@updateOrderDrafClient']);
            $router->post('orders/{order_id}/update', ['uses' => 'OrdersController@updateOrderClient']);
            $router->post('orders/{order_id}/update/status',  ['uses' => 'OrdersController@updateStatusOrder']);
            $router->post('orders/{order_id}/delete', ['uses' => 'OrdersController@deleteOrder']);
//            $router->get('orders', ['uses' => 'OrdersController@getClientOrders']);
        });
    });
});
