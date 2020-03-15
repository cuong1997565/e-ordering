<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->get('db1', ['uses' => 'Cqrs\DashboardController@test']);
    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
//        $router->get('contacts/', ['uses' => 'ContactsController@index']);
//        $router->get('contacts/form', ['uses' => 'ContactsController@getForm']);

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $permission_list_dn_orders = Permission::PERMISSION_LIST_ALL_DN_ORDER()->id;
            $permission_create_dn_order = Permission::PERMISSION_CREATE_DN_ORDER()->id;
            $permission_edit_dn_order = Permission::PERMISSION_EDIT_DN_ORDER()->id;
            $permission_update_dn_order = Permission::PERMISSION_UPDATE_DN_ORDER()->id;
            $permission_confirm_dn_order = Permission::PERMISSION_CONFIRM_UPDATE_DN_ORDER()->id;
            $permission_reverse_dn_order = Permission::PERMISSION_REVERSE_DN_ORDER()->id;
            $permission_approve_dn_order = Permission::PERMISSION_APPROVE_DN_ORDER()->id;
            $permission_reject_dn_order = Permission::PERMISSION_REJECT_DN_ORDER()->id;

            $router->post('dn/confirm', ['uses' => 'DeliveryNotesController@confirmDeliveryDirect', 'middleware' => "permission:{$permission_create_dn_order}"]);
            $router->post('dn', ['uses' => 'DeliveryNotesController@createDeliveryNote', 'middleware' => "permission:{$permission_create_dn_order}"]);
            $router->get('dn', ['uses' => 'DeliveryNotesController@index', 'middleware' => "permission:{$permission_list_dn_orders}"]);
            $router->get('dn/{id}', ['uses' => 'DeliveryNotesController@getDNById', 'middleware' => "permission:{$permission_edit_dn_order}"]);
            $router->post('dn/{delivery_note_id}/update', ['uses' => 'DeliveryNotesController@updateDeliveryNote', 'middleware' => "permission:{$permission_update_dn_order}"]);
            $router->post('dn/{delivery_note_id}/confirm', ['uses' => 'DeliveryNotesController@confirmDeliveryNote', 'middleware' => "permission:{$permission_confirm_dn_order}"]);
            $router->post('dn/{delivery_note_id}/reverse', ['uses' => 'DeliveryNotesController@revertDeliveyNote', 'middleware' => "permission:{$permission_reverse_dn_order}"]);
            $router->post('dn/{delivery_note_id}/approve', ['uses' => 'DeliveryNotesController@approveDnByAdmin', 'middleware' => "permission:{$permission_approve_dn_order}"]);
            $router->post('dn/{delivery_note_id}/reject',  ['uses' => 'DeliveryNotesController@rejectDnByAdmin', 'middleware' => "permission:{$permission_reject_dn_order}"]);
            $router->post('dn/{delivery_note_id}/approved-and-confirm', ['uses' => 'DeliveryNotesController@approveAndConfirmDnByAdmin']);
        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('dn/{id}', ['uses' => 'DeliveryNotesController@getClientDeliveryNote']);
        });
    });
});
