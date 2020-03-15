<?php

$router->group(['prefix' => API_PREFIX], function () use ($router) {
    $router->group(['middleware' => 'admin'], function () use ($router) {
        $router->get('dashboards/', ['uses' => 'Cqrs\DashboardController@getAll']);
        $router->get('dashboards/list', ['uses' => 'Cqrs\DashboardController@getAllList']);
    });
});
