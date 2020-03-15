<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->post('translations/form', ['uses' => 'TranslationsController@saveForm']);
    $router->get('translations/', ['uses' => 'TranslationsController@index']);
    $router->get('translations/form', ['uses' => 'TranslationsController@getForm']);
    $router->post('translations/delete', ['uses' => 'TranslationsController@delete']);
    $router->get('translations/get_trans', ['uses' => 'TranslationsController@getTrans']);


    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            //gen translation
            $router->get('translations/gen', ['uses' => 'TranslationsController@gen']);
            $router->get('translations/dev', ['uses' => 'TranslationsController@diffFromServer']);
            $router->post('translations/dev', ['uses' => 'TranslationsController@addToServer']);
        });
    });
    /* ---------- Auth api here } ---------- */
});
