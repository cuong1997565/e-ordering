<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_uoms = Permission::PERMISSION_LIST_UOMS()->id;
            $permission_create_uom = Permission::PERMISSION_CREATE_UOM()->id;
            $permission_edit_uom = Permission::PERMISSION_EDIT_UOM()->id;
            $router->get('uoms/', ['uses' => 'UomsController@getAll', 'middleware' => "permission:{$permission_list_uoms}"]);
            /*
             * filter is-based-uom about table uoms = true
             * */
            $router->post('uoms/is-based-uom', ['uses' => 'UomsController@getUomIsBasedUom']);
            $router->post('uoms/', ['uses' => 'UomsController@createUom', 'middleware' => "permission:{$permission_create_uom}"]);
            $router->post('uoms/{uom_id}', ['uses' => 'UomsController@updateUom', 'middleware' => "permission:{$permission_edit_uom}"]);
            $router->get('uom/{id}', ['uses' => 'UomsController@getSomeFieldWhereUom']);

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('uom/{id}', ['uses' => 'UomsController@getSomeFieldWhereUom']);

        });
    });
});
