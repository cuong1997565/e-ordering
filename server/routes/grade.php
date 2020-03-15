<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_grades = Permission::PERMISSION_LIST_GRADES()->id;
            $permission_create_grade = Permission::PERMISSION_CREATE_GRADE()->id;
            $permission_edit_grade = Permission::PERMISSION_EDIT_GRADE()->id;
            $router->get('grades/', ['uses' => 'GradeController@getAll', 'middleware' => "permission:{$permission_list_grades}"]);
            $router->post('grade/', ['uses' => 'GradeController@createGrade', 'middleware' => "permission:{$permission_create_grade}"]);
            $router->post('grade/{id}', ['uses' => 'GradeController@updateGrade', 'middleware' => "permission:{$permission_edit_grade}"]);
            $router->get('grade-items/' , ['uses' => 'GradeController@getSomeFieldGrade']);
            $router->get('grade-about-group/{id}' , ['uses' => 'GradeController@whereGradeGroup']);
            $router->get('grade-items/{grade_group_id}', ['uses' => 'GradeController@getIdGradeGroupAboutGrade']);
        });
    });
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('grade-items/{grade_group_id}', ['uses' => 'GradeController@getIdGradeGroupAboutGrade']);
        });
    });
});
