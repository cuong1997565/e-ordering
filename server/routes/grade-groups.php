<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_grade_groups = Permission::PERMISSION_LIST_GRADE_GROUPS()->id;
            $permission_create_grade_group = Permission::PERMISSION_CREATE_GRADE_GROUP()->id;
            $permission_edit_grade_group = Permission::PERMISSION_EDIT_GRADE_GROUP()->id;
            $router->get('grade-groups/', ['uses' => 'GradeGroupController@getAll', 'middleware' => "permission:{$permission_list_grade_groups}"]);
            $router->post('grade-group/', ['uses' => 'GradeGroupController@createGradeGroup', 'middleware' => "permission:{$permission_create_grade_group}"]);
            $router->post('grade-group/{id}', ['uses' => 'GradeGroupController@updateGradeGroup', 'middleware' => "permission:{$permission_edit_grade_group}"]);
            $router->get('grade-group-about-product', ['uses' => 'GradeGroupController@getGradeGroupAboutProduct']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
        });
    });
});
