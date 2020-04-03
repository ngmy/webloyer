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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('jsonrpc', function (Illuminate\Http\Request $request) {
        $server = new JsonRPC\Server;
        $middlewareHandler = $server->getMiddlewareHandler();
        $middlewareHandler->withMiddleware(new App\Services\Api\Middleware\Authenticate($request));
        $procedureHandler = $server->getProcedureHandler();
        $procedureHandler->withObject(app()->make('App\Services\Api\JsonRpc'));
        return $server->execute();
    });
});

Route::group(['prefix' => 'webhook/github/v1', 'middleware' => 'github_webhook_secret'], function () {
    Route::resource('projects.deployments', 'Webhook\Github\V1\DeploymentsController', [
        'only' => ['store']
    ]);
});
