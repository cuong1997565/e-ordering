<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_categories = Permission::PERMISSION_LIST_CATEGORIES()->id;
            $permission_create_category = Permission::PERMISSION_CREATE_CATEGORY()->id;
            $permission_edit_category = Permission::PERMISSION_EDIT_CATEGORY()->id;
//            $permission_delete_category = Permission::PERMISSION_DELETE_CATEGORY()->id;
            $router->post('categories', ['uses' => 'CategoriesController@createCategory', 'middleware' => "permission:{$permission_create_category}"]);
            $router->post('categories/{category_id}', ['uses' => 'CategoriesController@updateCategory', 'middleware' => "permission:{$permission_edit_category}"]);
            $router->get('categories/search', ['uses' => 'CategoriesController@searchCategoryByName']);
            $router->get('categories', ['uses' => 'CategoriesController@index', 'middleware' => "permission:{$permission_list_categories}"]);
            $router->post('categories/{category_id}/delete', ['uses' => 'CategoriesController@delete']);
            $router->get('categories/name/{category_name}', ['uses' => 'CategoriesController@getCategoryByName']);
            $router->get('categories-about-product', ['uses' => 'CategoriesController@getCategoryAboutProduct']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('categories', ['uses' => 'CategoriesController@getClientCategory']);
            $router->get('parent_category', ['uses' => 'CategoriesController@getClientParentCategory']);
        });
    });
});
