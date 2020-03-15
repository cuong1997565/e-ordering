<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
        $router->get('logs', 'LogViewerController@index');
    });
    $router->get('/', function (){return 'This is api page.';});
    $router->post('/update-order', ['uses' => 'CommonsController@updateOrder']);
    $router->get('/static-data', ['uses' => 'CommonsController@staticData']);

    /** Commons Route */
    $router->group(['prefix' => 'commons'], function() use ($router)
    {
        $router->get('/env', ['uses' => 'CommonsController@env']);
        $router->post('/saveError', ['uses' => 'CommonsController@saveError']);

        $router->group(['middleware' => 'auth'], function () use ($router)
        {
            $router->post('/active', ['uses' => 'CommonsController@active']);
        });
    });
});

