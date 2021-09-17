<?php

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Exceptions\AppException;
use App\Http\Controllers\ApiV0Controller;

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
$router->get('/hlaseni', [
    'as' => 'submissionForm',
    'uses' => '\App\Http\Controllers\SubmissionController@show'
]);
$router->get('/kalendar', [
    'as' => 'calendar',
    'uses' => '\App\Http\Controllers\CalendarController@download'
]);
$router->get('/lang/{lang}', [
    'as' => 'lang', function ($lang) {
        if (in_array($lang, config('ctvero.locales'))) {
            request()->session()->put('locale', $lang);
            if (str_starts_with(request()->header('referer'), config('app.url'))) {
                return redirect(request()->header('referer'));
            }
            return redirect(route('index'));
        }
        throw new AppException(422, array('Neznámá lokalizace'));
    }
]);
$router->post('/message', [
    'as' => 'message',
    'uses' => '\App\Http\Controllers\MessageController@send'
]);
$router->post('/submission', [
    'as' => 'submission',
    'uses' => '\App\Http\Controllers\SubmissionController@submit'
]);
$router->get('/vysledky', [
    'as' => 'results',
    'uses' => '\App\Http\Controllers\ResultsController@show'
]);
$router->get('/api/v0/{category}/{endpoint}', [
    'as' => 'apiV0', function ($category, $endpoint) {
        $registeredMethods = [
            'appMigrate',
        ];
        $method = Str::camel($category . '_' . $endpoint);
        if (! in_array($method, $registeredMethods)) {
            throw new NotFoundHttpException();
        }
        return (new ApiV0Controller)->$method();
    }
]);
