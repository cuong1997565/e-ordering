<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_features = Permission::PERMISSION_LIST_FEATURES()->id;
            $permission_create_feature = Permission::PERMISSION_CREATE_FEATURE()->id;
            $permission_edit_feature = Permission::PERMISSION_EDIT_FEATURE()->id;
            $router->get('features/', ['uses' => 'FeaturesController@getAll', 'middleware' => "permission:{$permission_list_features}"]);
            $router->post('features/', ['uses' => 'FeaturesController@createFeatures', 'middleware' => "permission:{$permission_create_feature}"]);
            $router->post('features/{id}', ['uses' => 'FeaturesController@updateFeatures', 'middleware' => "permission:{$permission_edit_feature}"]);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
