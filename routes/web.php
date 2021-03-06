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
    //echo "Hook is working";
});

$router->group(['prefix' => 'api'], function() use($router)
{
    $router->post('/register','UserController@register');
    $router->post('/login','UserController@login');
    //$router->get('/show','UserController@show');
    $router->post('/forgotpassword','ForgotPasswordController@forgotpassword');
    $router->post('/resetpassword','ForgotPasswordController@resetverify');
    $router->get('/details','UserController@details');
});

$router->group(['prefix' => 'api','middleware' => 'auth'], function() use($router)
{
    $router->get('/show','UserController@show'); 
});


