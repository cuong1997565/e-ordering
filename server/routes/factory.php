<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_factories = Permission::PERMISSION_LIST_FACTORIES()->id;
            $permission_create_factory = Permission::PERMISSION_CREATE_FACTORY()->id;
            $permission_edit_factory = Permission::PERMISSION_EDIT_FACTORY()->id;
//            $permission_delete_factory = Permission::PERMISSION_DELETE_FACTORY()->id;
            $router->get('factories/', ['uses' => 'FactoriesController@getAll', 'middleware' => "permission:{$permission_list_factories}"]);
            $router->post('factories/', ['uses' => 'FactoriesController@createFactory', 'middleware' => "permission:{$permission_create_factory}"]);
            $router->post('factories/{factory_id}', ['uses' => 'FactoriesController@updateFactory', 'middleware' => "permission:{$permission_edit_factory}"]);
            $router->post('factories/{factory_id}/delete', ['uses' =>'FactoriesController@delete']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('factories', ['uses' => 'FactoriesController@getClientFactory']);
            $router->get('factories_client', ['uses' => 'FactoriesController@getClientFactories']);
        });
    });
});
