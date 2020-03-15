<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->get('langs/get_langs/', ['uses' => 'LangsController@getLangs']);

    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $router->post('langs/form', ['uses' => 'LangsController@saveForm']);
            $router->get('langs/', ['uses' => 'LangsController@index']);
            $router->get('langs/form', ['uses' => 'LangsController@getForm']);
            $router->post('langs/delete', ['uses' => 'LangsController@delete']);
        });
    });
    /* ---------- Auth api here } ---------- */
});
