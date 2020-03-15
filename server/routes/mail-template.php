<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */

    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->get('mail-templates/', ['uses' => 'MailTemplatesController@index']);

    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $router->get('mail-templates/', ['uses' => 'MailTemplatesController@index']);
            $router->get('mail-templates/form', ['uses' => 'MailTemplatesController@getForm']);
            $router->post('mail-templates/form', ['uses' => 'MailTemplatesController@saveForm']);
            $router->post('mail-templates/delete', ['uses' => 'MailTemplatesController@delete']);
        });

        // Api for member only
        $router->group(['middleware' => 'member'], function () use ($router)
        {

        });
    });
    /* ---------- Auth api here } ---------- */
});
