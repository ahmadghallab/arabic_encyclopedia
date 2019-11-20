<?php

$router->group(['prefix' => 'api/v1'], function() use ($router) {
    // User routes
    $router->post('user/register', 'AuthController@store');
    $router->post('user/signin', 'AuthController@signin');
    $router->get('user/{id}', 'AuthController@show');
    $router->patch('user/{id}', 'AuthController@update');

    // Article routes
    $router->get('articles', 'ArticleController@index');
    $router->get('article/{id}', 'ArticleController@show');
    $router->get('article/{id}/withrelated', 'ArticleController@showWithRelated');
    $router->post('articles', 'ArticleController@store');
    $router->patch('article/{id}', 'ArticleController@update');
    $router->delete('article/{id}', 'ArticleController@destroy');
    $router->post('article/{id}/image', 'ArticleController@updateArticleImage');
    $router->get('article/image/{image_name}', 'ArticleController@getArticleImage');

    // Topic routes
    $router->get('topics', 'TopicController@index');
    $router->post('topics', 'TopicController@store');
    $router->get('topic/{id}', 'TopicController@show');
    $router->patch('topic/{id}', 'TopicController@update');
    $router->delete('topic/{id}', 'TopicController@destroy'); 
});
