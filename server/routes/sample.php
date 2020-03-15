<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */

    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Sample
        $router->get('samples/', ['uses' => 'SamplesController@index']);
        $router->get('samples/form', ['uses' => 'SamplesController@getForm']);
        $router->post('samples/form', ['uses' => 'SamplesController@saveForm']);
        $router->post('samples/delete', ['uses' => 'SamplesController@delete']);

        // Sample type
        $router->get('sample_types/', ['uses' => 'SampleTypesController@index']);
        $router->get('sample_types/form', ['uses' => 'SampleTypesController@getForm']);
        $router->post('sample_types/form', ['uses' => 'SampleTypesController@saveForm']);
        $router->post('sample_types/delete', ['uses' => 'SampleTypesController@delete']);

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
