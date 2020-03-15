<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_feature_items = Permission::PERMISSION_LIST_FEATURE_ITEMS()->id;
            $permission_create_feature_item = Permission::PERMISSION_CREATE_FEATURE_ITEM()->id;
            $permission_edit_feature_item = Permission::PERMISSION_EDIT_FEATURE_ITEM()->id;
            $router->get('feature-items/', ['uses' => 'FeatureItemsController@getAll', 'middleware' => "permission:{$permission_list_feature_items}"]);
            $router->post('feature-items/', ['uses' => 'FeatureItemsController@createFeatureItems', 'middleware' => "permission:{$permission_create_feature_item}"]);
            $router->post('feature-items/{id}', ['uses' => 'FeatureItemsController@updateFeatureItems', 'middleware' => "permission:{$permission_edit_feature_item}"]);
            $router->get('feature-items-about-product/', ['uses' => 'FeatureItemsController@getFeatureItemAboutProduct']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('feature-items-about-product/', ['uses' => 'FeatureItemsController@getFeatureItemAboutProduct']);

        });
    });
});
