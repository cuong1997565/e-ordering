<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
     $router->group(['middleware' => 'auth'], function () use ($router)
     {
         // Api for admin only
         $router->group(['middleware' => 'admin'], function () use ($router)
         {
         $permission_list_stores = Permission::PERMISSION_LIST_STORES()->id;
         $permission_create_store = Permission::PERMISSION_CREATE_STORE()->id;
         $permission_edit_store = Permission::PERMISSION_EDIT_STORE()->id;
//         $permission_delete_store = Permission::PERMISSION_DELETE_STORE()->id;
            $router->get('stores', ['uses' => 'StoresController@getAll', 'middleware' => "permission:{$permission_list_stores}"]);
            $router->post('stores', ['uses' => 'StoresController@createStore', 'middleware' => "permission:{$permission_create_store}"]);
            $router->post('stores/{store_id}', ['uses' => 'StoresController@updateStore', 'middleware' => "permission:{$permission_edit_store}"]);
            $router->post('stores/{stores_id}/delete', ['uses' => 'StoresController@delete']);
             $router->get('product-stores', ['uses' => 'StoresController@getSomeFieldStore']);
         });
     });
            $router->get('distributor/name/{ditributor_name}/stores', ['uses' => 'StoresController@getDistributorByName']);
    /* ---------- Auth api here } ---------- */
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
