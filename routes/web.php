<?php

$router->group(['prefix' => 'api/v1'], function() use ($router) {
    // User routes
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
    $router->post('articles', [
        'uses' => 'ArticleController@store'
    ]);
    $router->post('article/{id}', [
        'uses' => 'ArticleController@update'
    ]);
    $router->get('article/image/{image_name}', [
        'uses' => 'ArticleController@getArticleImage'
    ]);

    // Topic routes
    $router->get('topics', [
        'uses' => 'TopicController@index'
    ]);
    $router->post('topics', [
        'uses' => 'TopicController@store'
    ]);
    $router->put('topic/{id}', [
        'uses' => 'TopicController@update'
    ]);
    $router->delete('topic/{id}', [
        'uses' => 'TopicController@destroy'
    ]);
});
