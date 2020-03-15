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
             $permission_list_areas = Permission::PERMISSION_LIST_AREAS()->id;
             $permission_create_area = Permission::PERMISSION_CREATE_AREA()->id;
             $permission_edit_area = Permission::PERMISSION_EDIT_AREA()->id;
//             $permission_delete_area = Permission::PERMISSION_DELETE_AREA()->id;
             // Commune
             $router->post('communes/{commune_id}/delete', ['uses' => 'CommunesController@delete']);

             // District
             $router->post('districts/{district_id}/delete', ['uses' => 'DistrictsController@delete']);

             // Province
             $router->post('provinces/{province_id}/delete', ['uses' => 'ProvincesController@delete']);

             // Village
             $router->post('villages/{village_id}/delete', ['uses' => 'VillagesController@delete']);

             //area
             $router->get('areas',['uses' => 'AreasController@index', 'middleware' => "permission:{$permission_list_areas}"]);
             $router->post('areas',['uses' => 'AreasController@createArea', 'middleware' => "permission:{$permission_create_area}"]);
             $router->post('areas/{area_id}',['uses' => 'AreasController@updateArea', 'middleware' => "permission:{$permission_edit_area}"]);

         });
     });
    /* ---------- Auth api here } ---------- */
});
