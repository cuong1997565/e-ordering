<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $router->get('credit-transactions', ['uses' => 'CreditTransactionsController@index']);
            $router->post('credit-transactions', ['uses' => 'CreditTransactionsController@createCreditTransaction']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {

    });
});
