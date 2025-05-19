<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

$router->group(['middleware' => 'role'], function () use ($router) {

    // ADMIN GROUP
    $router->group(['middleware' => 'role:admin'], function () use ($router) {
        $router->get('/users/all', 'UserController@index');
        $router->get('/users/{id}', 'UserController@show');
        $router->post('/users', 'UserController@store');
        $router->put('/users/{id}', 'UserController@update');
        $router->delete('/users/{id}', 'UserController@destroy');

        $router->get('/posts/all', 'PostsController@index');
        $router->get('/posts/{id}', 'PostsController@show');
        $router->post('/posts', 'PostsController@store');
        $router->put('/posts/{id}', 'PostsController@update');
        $router->delete('/posts/{id}', 'PostsController@destroy');

        $router->get('/comments/all', 'CommentController@index');
        $router->get('/comments/{id}', 'CommentController@show');
        $router->post('/comments', 'CommentController@store');
        $router->put('/comments/{id}', 'CommentController@update');
        $router->delete('/comments/{id}', 'CommentController@destroy');
    });

    // PEMBACA GROUP
    $router->group(['middleware' => 'role:pembaca'], function () use ($router) {
        $router->get('/users/all', 'UserController@index');
        $router->get('/users/{id}', 'UserController@show');

        $router->get('/posts/all', 'PostsController@index');
        $router->get('/posts/{id}', 'PostsController@show');

        $router->get('/comments/all', 'CommentController@index');
        $router->get('/comments/{id}', 'CommentController@show');
    });

    // PENULIS GROUP
    $router->group(['middleware' => 'role:penulis'], function () use ($router) {
        $router->post('/users', 'UserController@store');
        
        $router->post('/posts', 'PostsController@store');

        $router->post('/comments', 'CommentController@store');
    }); 

    // EDITOR GROUP
    $router->group(['middleware' => 'role:editor'], function () use ($router) {
        $router->put('/users/{id}', 'UserController@update');

        $router->put('/posts/{id}', 'PostsController@update');

        $router->put('/posts/{id}', 'PostsController@update');

        $router->put('/comments/{id}', 'CommentController@update');
    });

    $router->post('/me', 'AuthController@me');
    $router->post('/logout', 'AuthController@logout');

});
