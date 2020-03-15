<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */

    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->get('static-contents/', ['uses' => 'StaticContentsController@index']);
    
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Sample
        $router->post('static-contents/init', ['uses' => 'StaticContentsController@init']);
        $router->post('static-contents/save', ['uses' => 'StaticContentsController@save']);
        $router->post('static-contents/', ['uses' => 'StaticContentsController@index']);

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
