<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_attribute_list_of_values = Permission::PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES()->id;
            $permission_create_attribute_list_of_value = Permission::PERMISSION_CREATE_ATTRIBUTE_LIST_OF_VALUE()->id;
            $permission_edit_attribute_list_of_value = Permission::PERMISSION_EDIT_ATTRIBUTE_LIST_OF_VALUE()->id;
            $router->get('attribute-lists-of-value/', ['uses' => 'AttributeListsOfValueController@getAll', 'middleware' => "permission:{$permission_list_attribute_list_of_values}"]);
            $router->post('attribute-lists-of-value/', ['uses' => 'AttributeListsOfValueController@createAttributeListsOfValue', 'middleware' => "permission:{$permission_create_attribute_list_of_value}"]);
            $router->post('attribute-lists-of-value/{id}', ['uses' => 'AttributeListsOfValueController@updateAttributeListsOfValue', 'middleware' => "permission:{$permission_edit_attribute_list_of_value}"]);
            $router->get('attribute-lists-of-value-some-field',  ['uses' => 'AttributeListsOfValueController@getSomeAttributeList']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('attribute-lists-of-value/', ['uses' => 'AttributeListsOfValueController@getAll']);
            $router->post('attribute-lists-of-value-some-field',  ['uses' => 'AttributeListsOfValueController@getSomeAttributeList']);
        });
    });
});
