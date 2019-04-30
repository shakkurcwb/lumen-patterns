<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Users module
$router->group(['prefix' => 'users'], function () use ($router) {

    // List all users (with filters and sorters)
    $router->get('/', ['uses' => 'UsersController@list']);

    // Get an specific user by id
    $router->get('/{id}', ['uses' => 'UsersController@get']);

    // Search for users (with sorters)
    $router->post('/search', ['uses' => 'UsersController@search']);

    // Create new user
    $router->post('/', ['uses' => 'UsersController@store']);

});
