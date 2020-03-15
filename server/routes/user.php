<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */
    $router->post('users/login', ['uses' => 'UsersController@login']);
    $router->post('users/register', ['uses' => 'UsersController@register']);
    $router->post('users/resetPassword', ['uses' => 'UsersController@resetPassword']);
    $router->post('users/resetPassword-activation', ['uses' => 'UsersController@resetPasswordActivation']);
    $router->get('users/checkToken', ['uses' => 'UsersController@check']);
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $permission_list_admins = Permission::PERMISSION_LIST_ADMINS()->id;
            $permission_view_admin = Permission::PERMISSION_VIEW_ADMIN()->id;
//            $permission_delete_admin = Permission::PERMISSION_DELETE_ADMIN()->id;
            $router->get('users/', ['uses' => 'UsersController@index', 'middleware' => "permission:{$permission_list_admins}"]);
            $router->get('users/check', ['uses' => 'UsersController@checkToken']);
            $router->get('users/form', ['uses' => 'UsersController@getForm']);
            $router->post('users/form', ['uses' => 'UsersController@saveForm']);
            $router->post('users/delete', ['uses' => 'UsersController@delete']);
            $router->get('users/profile', ['uses' => 'UsersController@getProfile']);
            $router->post('users/profile', ['uses' => 'UsersController@updateProfile']);
            $router->get('users/logout', ['uses' => 'UsersController@logout']);
            $router->post('users/activation', ['uses' => 'UsersController@activation']);
            $router->post('users/password', ['uses' => 'UsersController@password']);
            $router->post('users/updateprofile', ['uses' => 'UsersController@updateProfile']);
            $router->get('/users/detail', ['uses' => 'UsersController@detail', 'middleware' => "permission:{$permission_view_admin}"]);
        });

        // Api for member only
        $router->group(['middleware' => 'member'], function () use ($router)
        {

        });
    });
    /* ---------- Auth api here } ---------- */
});
