<?php

$router->group(['prefix' => 'api/v1'], function() use ($router) {
    // Users routes
    $router->post('user', [
        'uses' => 'AuthController@store'
    ]);

    $router->post('user/signin', [
        'uses' => 'AuthController@signin'
    ]);

    // Article routes
    $router->get('articles', [
        'uses' => 'ArticleController@index'
    ]);
    $router->get('article/{id}', [
        'uses' => 'ArticleController@show'
    ]);
    $router->post('article', [
        'uses' => 'ArticleController@store'
    ]);
});
