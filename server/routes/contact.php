<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */
    $router->post('contacts/form', ['uses' => 'ContactsController@saveForm']);
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        $router->get('contacts/', ['uses' => 'ContactsController@index']);
        $router->get('contacts/form', ['uses' => 'ContactsController@getForm']);

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {
            $router->post('contacts/delete', ['uses' => 'ContactsController@delete']);
        });
    });
    /* ---------- Aut h api here } ---------- */
});
