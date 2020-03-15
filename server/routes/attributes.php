<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_attributes = Permission::PERMISSION_LIST_ATTRIBUTES()->id;
            $permission_create_attribute = Permission::PERMISSION_CREATE_ATTRIBUTE()->id;
            $permission_edit_attribute = Permission::PERMISSION_EDIT_ATTRIBUTE()->id;
            $router->get('attributes/', ['uses' => 'AttributesController@getAll', 'middleware' => "permission:{$permission_list_attributes}"]);
            $router->post('attributes/', ['uses' => 'AttributesController@createAttributes', 'middleware' => "permission:{$permission_create_attribute}"]);
            $router->post('attributes/{id}', ['uses' => 'AttributesController@updateAttributes', 'middleware' => "permission:{$permission_edit_attribute}"]);
            $router->get('attribute-item/{id}', ['uses' => 'AttributesController@getSomeFieldWhereAttribute']);

        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('attribute-item/{id}', ['uses' => 'AttributesController@getSomeFieldWhereAttribute']);
        });
    });
});
