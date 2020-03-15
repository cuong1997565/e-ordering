<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */

    $router->get('menus/listMenus', ['uses' => 'MenusController@listMenus']);

    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Sample
        $router->get('menus/', ['uses' => 'MenusController@index']);
        $router->get('menus/form', ['uses' => 'MenusController@getForm']);
        $router->post('menus/form', ['uses' => 'MenusController@saveForm']);
        $router->post('menus/delete', ['uses' => 'MenusController@delete']);
        $router->post('menus/update-order', ['uses' => 'MenusController@updateOrder']);

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {

        });

        // Api for member only
        $router->group(['middleware' => 'member'], function () use ($router)
        {

        });
    });
    /* ---------- Auth api here } ---------- */
});
