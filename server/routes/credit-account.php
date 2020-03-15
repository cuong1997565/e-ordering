<?php

use App\Models\Permission;

$router->group(['prefix' => API_PREFIX], function () use ($router) {

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $permission_list_credit_accounts = Permission::PERMISSION_LIST_CREDIT_ACCOUNTS()->id;
            $permission_create_credit_account = Permission::PERMISSION_CREATE_CREDIT_ACCOUNT()->id;
            $permission_edit_credit_account = Permission::PERMISSION_EDIT_CREDIT_ACCOUNT()->id;
//            $permission_delete_credit_account = Permission::PERMISSION_DELETE_CREDIT_ACCOUNT()->id;
            $router->post('credit-accounts', ['uses' => 'CreditAccountsController@createCreditAccount', 'middleware' => "permission:{$permission_create_credit_account}"]);
            $router->post('credit-accounts/{credit_account_id}', ['uses' => 'CreditAccountsController@updateCreditAccount', 'middleware' => "permission:{$permission_edit_credit_account}"]);
            $router->get('credit-accounts', ['uses' => 'CreditAccountsController@index', 'middleware' => "permission:{$permission_list_credit_accounts}"]);
            $router->post('credit-accounts/{id}/delete', ['uses' => 'CreditAccountsController@delete']);
            $router->get('credit-account-distributor', ['uses' => 'CreditAccountsController@creditAccountAndDistributor']);
        });

        $router->group(['middleware' => 'member'], function () use ($router) {

        });
    });

    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->get('get-some-field-credit-account/{distributor_id}', ['uses' => 'CreditAccountsController@getSomeFieldCreditAccount']);
    });
});
