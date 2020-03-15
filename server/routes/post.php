<?php

$router->group(['prefix' => API_PREFIX], function() use ($router)
{
    /* ---------- Public api here { ---------- */
    $router->get('post/category', ['uses' => 'PostsController@getPostInCategories']);
    $router->get('post/category/detail', ['uses' => 'PostsController@getPostInCategory']);

    $router->get('post/detail', ['uses' => 'PostsController@getDetail']);
    /* ---------- Public api here } ---------- */

    /* ---------- Auth api here { ---------- */
    $router->group(['middleware' => 'auth'], function () use ($router)
    {
        // Post
        $router->get('posts/', ['uses' => 'PostsController@index']);
        $router->get('posts/form', ['uses' => 'PostsController@getForm']);
        $router->post('posts/form', ['uses' => 'PostsController@saveForm']);
        $router->post('posts/delete', ['uses' => 'PostsController@delete']);
        $router->get('posts/list_category_post', ['uses' => 'PostsController@getListPostNotInCategory']);
        $router->post('posts/update_order', ['uses' => 'PostsController@updateOrder']);

        // Category post
        $router->get('categories_posts/', ['uses' => 'CategoryPostsController@index']);
        $router->post('categories_posts/delete', ['uses' => 'CategoryPostsController@delete']);
        $router->post('categories_posts', ['uses' => 'CategoryPostsController@add']);

        // Category
        $router->get('category/system', ['uses' => 'CategoriesController@categorySystem']);
        $router->get('category/tree', ['uses' => 'CategoriesController@categoryTree']);

//        $router->get('categories/form', ['uses' => 'CategoriesController@getForm']);
//        $router->post('categories/form', ['uses' => 'CategoriesController@saveForm']);
//        $router->post('categories/delete', ['uses' => 'CategoriesController@delete']);

        // Api for admin only
        $router->group(['middleware' => 'admin'], function () use ($router)
        {

        });

        // Api for member only
        $router->group(['middleware' => 'member'], function () use ($router)
        {

        });
    });
    /* ---------- Auth api here } ---------- */
});
