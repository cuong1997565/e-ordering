<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    /* ---------- Public api here { ---------- */

    $router->post('customers', ['uses' => 'CustomersController@createCustomer']);
    $router->get('customers/name/{customer_name}', ['uses' => 'CustomersController@getCustomerByName']);
    $router->get('customers/search', ['uses' => 'CustomersController@searchCustomersByName']);
    $router->get('customers/store/{store_id}', ['uses' => 'CustomersController@getCustomerByStoreId']);
    $router->post('customers/{customer_id}', ['uses' => 'CustomersController@updateCustomer']);
    $router->post('customers/{customer_id}/delete', ['uses' => 'CustomersController@deleteCustomer']);
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
//        $router->get('contacts/', ['uses' => 'ContactsController@index']);
//        $router->get('contacts/form', ['uses' => 'ContactsController@getForm']);

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $permission_list_members = Permission::PERMISSION_LIST_MEMBERS()->id;
            $permission_view_member = Permission::PERMISSION_VIEW_MEMBER()->id;
            $permission_edit_member = Permission::PERMISSION_EDIT_MEMBER()->id;
//            $permission_delete_member = Permission::PERMISSION_DELETE_MEMBER()->id;
            $router->get('customers/', ['uses' => 'CustomersController@index', 'middleware' => "permission:{$permission_list_members}"]);
            $router->get('customers/form', ['uses' => 'CustomersController@getForm', 'middleware' => "permission:{$permission_edit_member}"]);
            $router->post('customers/admin/form', ['uses' => 'CustomersController@saveForm']);
            $router->post('customers/admin/del', ['uses' => 'CustomersController@delete']);
            $router->get('/customers/detail', ['uses' => 'CustomersController@detail', 'middleware' => "permission:{$permission_view_member}"]);
        });
    });
    /* ---------- Auth api here } ---------- */
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('users/{customer_id}', ['uses' => 'CustomersController@getUser']);
            $router->post('staffs', ['uses' => 'CustomersController@createStaff']);
            $router->post('staffs/{customer_id}', ['uses' => 'CustomersController@updateStaff']);
            $router->get('customers/', ['uses' => 'CustomersController@getAllCustomer']);
            $router->get('customers/{customer_id}', ['uses' => 'CustomersController@getCustomerByID']);
            $router->get('customer_search', ['uses' => 'CustomersController@getClientSreachCustomers']);
        });
        $router->post('customers/changeActiveClient', ['uses' => 'CustomersController@changeActiveClient']);
        $router->post('users/login', ['uses' => 'CustomersController@login']);
        $router->get('users/checkTime/{time}', ['uses' => 'CustomersController@checkTime']);
        $router->post('users/forgot-password', ['uses' => 'CustomersController@forgotPass']);
        $router->post('users/forgot-change', ['uses' => 'CustomersController@changePass']);
        $router->post('users/change-email', ['uses' => 'CustomersController@changeEmail']);
    });
});
