<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers'
], function ($api) {
    // Standard Routes
    $api->group(['namespace' => '\Api'], function ($api) {
        $api->resource('users', 'UsersController');
        $api->resource('classrooms', 'ClassroomsController');
        $api->resource('assignments', 'TasksController');
        $api->resource('questions', 'QuestionsController');
        $api->resource('answers', 'AnswersController');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
