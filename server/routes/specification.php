<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_specifications = Permission::PERMISSION_LIST_SPECIFICATIONS()->id;
            $permission_create_specification = Permission::PERMISSION_CREATE_SPECIFICATION()->id;
            $permission_edit_specification = Permission::PERMISSION_EDIT_SPECIFICATION()->id;
            $router->post('specifications', ['uses' => 'SpecificationsController@createSpecification', 'middleware' => "permission:{$permission_create_specification}"]);
            $router->post('specifications/{specification_id}', ['uses' => 'SpecificationsController@updateSpecification', 'middleware' => "permission:{$permission_edit_specification}"]);
            $router->get('specifications', ['uses' => 'SpecificationsController@index', 'middleware' => "permission:{$permission_list_specifications}"]);
            $router->get('specification_product', ['uses' => 'SpecificationsController@getSpecificationProduct']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
    });
});
