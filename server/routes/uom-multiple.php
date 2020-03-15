<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_uom_multiples = Permission::PERMISSION_LIST_UOM_MULTIPLES()->id;
            $permission_create_uom_multiple = Permission::PERMISSION_CREATE_UOM_MULTIPLE()->id;
            $permission_edit_uom_multiple = Permission::PERMISSION_EDIT_UOM_MULTIPLE()->id;
            $router->get('uom-multiples/', ['uses' => 'UomMultipleController@getAll', 'middleware' => "permission:{$permission_list_uom_multiples}"]);
            $router->post('uom-multiples/', ['uses' => 'UomMultipleController@createUomMultiples', 'middleware' => "permission:{$permission_create_uom_multiple}"]);
            $router->post('uom-multiples/{uom_multiples_id}', ['uses' => 'UomMultipleController@updateUomMultiples', 'middleware' => "permission:{$permission_edit_uom_multiple}"]);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
