<?php

$router->group(['prefix' => 'api/v1'], function() use ($router) {
    // User routes
    $router->post('user', 'AuthController@store');
    $router->post('user/signin', 'AuthController@signin');

    // Article routes
    $router->get('articles', 'ArticleController@index');
    $router->get('article/{id}', 'ArticleController@show');
    $router->post('articles', 'ArticleController@store');
    $router->patch('article/{id}', 'ArticleController@update');
    $router->post('article/{id}/image', 'ArticleController@updateArticleImage');
    $router->get('article/image/{image_name}', 'ArticleController@getArticleImage');

    // Topic routes
    $router->get('topics', 'TopicController@index');
    $router->post('topics', 'TopicController@store');
    $router->patch('topic/{id}', 'TopicController@update');
    $router->delete('topic/{id}', 'TopicController@destroy');
});
