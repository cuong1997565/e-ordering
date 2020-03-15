<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $permission_list_roles = Permission::PERMISSION_LIST_ROLES()->id;
            $permission_create_role = Permission::PERMISSION_CREATE_ROLE()->id;
            $permission_edit_role = Permission::PERMISSION_EDIT_ROLE()->id;
//            $permission_delete_role = Permission::PERMISSION_DELETE_ROLE()->id;
            $router->post('roles', ['uses' => 'RolesController@createRole', 'middleware' => "permission:{$permission_create_role}"]);
            $router->post('roles/{role_id}', ['uses' => 'RolesController@updateRole', 'middleware' => "permission:{$permission_edit_role}"]);
            $router->post('roles/{role_id}/delete', ['uses' => 'RolesController@deleteRole']);
            $router->get('roles', ['uses' => 'RolesController@getAllRoles', 'middleware' => "permission:{$permission_list_roles}"]);
            $router->get('roles/{role_id}', ['uses' => 'RolesController@detailRole']);
            $router->get('permissions', ['uses' => 'RolesController@getAllPermissions']);
        });
    });
    /* ---------- Auth api here } ---------- */
});
