<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_distributors = Permission::PERMISSION_LIST_DISTRIBUTORS()->id;
            $permission_create_distributor = Permission::PERMISSION_CREATE_DISTRIBUTOR()->id;
            $permission_update_distributor = Permission::PERMISSION_EDIT_DISTRIBUTOR()->id;
//            $permission_delete_distributor = Permission::PERMISSION_DELETE_DISTRIBUTOR()->id;
            $permission_remove_product_from_distributor = Permission::PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR()->id;
            $router->get('distributors', ['uses' => 'DistributorsController@index', 'middleware' => "permission:{$permission_list_distributors}"]);
            $router->post('distributors', ['uses' => 'DistributorsController@createDistributor', 'middleware' => "permission:{$permission_create_distributor}"]);
            $router->post('distributors/{distributor_id}', ['uses' => 'DistributorsController@updateDistributor', 'middleware' => "permission:{$permission_update_distributor}"]);
            $router->post('distributors/{distributor_id}/delete', ['uses' => 'DistributorsController@delete']);
            $router->post('distributors/{distributor_id}/deleteDistributorProduct', ['uses' => 'DistributorsController@deleteDistributorProduct', 'middleware' => "permission:{$permission_remove_product_from_distributor}"]);
            $router->get('distributors/checkCustomer', ['uses' => 'DistributorsController@checkCustomer']);
            $router->get('distributors/checkCreditAccount', ['uses' => 'DistributorsController@checkCreditAccount']);
            $router->get('distributors/active', ['uses' => 'DistributorsController@getDitributorActive']);
//            $router->get('distributors/search', ['uses' => 'DistributorsController@searchDistributorsByName']);
//            $router->get('distributors/name/{distributor_name}', ['uses' => 'DistributorsController@getDistributorByName']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->group(['middleware' => 'member'], function () use ($router) {
            $router->get('distributors', ['uses' => 'DistributorsController@indexClient']);
        });
    });
});
