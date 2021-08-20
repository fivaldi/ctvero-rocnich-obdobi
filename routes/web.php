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

$router->get('/', [
    'as' => 'index',
    'uses' => '\App\Http\Controllers\IndexController@show'
]);
$router->get('/hlaseni', function () use ($router) {
    return view('submission');
});
$router->post('/message', [
    'as' => 'message',
    'uses' => '\App\Http\Controllers\MessageController@send'
]);
$router->get('/vysledky', [
    'as' => 'results',
    'uses' => '\App\Http\Controllers\ResultsController@show'
]);
