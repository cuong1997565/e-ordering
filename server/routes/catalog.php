<?php

$router->group(['prefix' => API_PREFIX], function () use ($router)
{
    $router->group(['middleware' => 'auth'], function () use ($router){

        $router->group(['middleware' => 'admin'], function () use ($router){
            $router->get('catalogs', ['uses' => 'CatalogsController@getListFile']);
            $router->post('catalogs/save-file', ['uses' => 'CatalogsController@saveFileForm']);
            $router->post('catalogs/{catalog_id}', ['uses' => 'CatalogsController@updateCatalog']);
            $router->post('catalogs/{catalog_id}/delete', ['uses' =>'CatalogsController@delete']);
        });

        $router->group(['middleware' => 'member'], function () use ($router){

        });
    }) ;
    $router->group(['prefix' => API_CLIENT_PREFIX], function () use ($router) {
        $router->get('catalogs', ['uses' => 'CatalogsController@getListFile']);
        $router->get('catalogs/filePath', ['uses' => 'CatalogsController@downFile']);
    });
});
