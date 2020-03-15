<?php

$router->group(['prefix' => 'admin'], function() use ($router)
{
    $router->get('{any:.*}', function ($router)
    {
        return file_get_contents('admin/index.html');
    });
});

$router->get('{any:.*}', function ($router)
{
    return file_get_contents('index.html');
});
