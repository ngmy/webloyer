<?php

use Illuminate\Http\Request;
use JsonRPC\Server;
use Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\Middleware\Authenticate;
use Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\JsonRpc;

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

Route::group(['prefix' => 'v1'], function () {
    Route::post('jsonrpc', function (Request $request) {
        $server = new Server();
        $middlewareHandler = $server->getMiddlewareHandler();
        $middlewareHandler->withMiddleware(app()->make(Authenticate::class, [$request]));
        $procedureHandler = $server->getProcedureHandler();
        $procedureHandler->withObject(app()->make(JsonRpc::class));
        return $server->execute();
    });
});

Route::group(['prefix' => 'webhook/github/v1', 'middleware' => 'github_webhook_secret'], function () {
    Route::resource('projects.deployments', 'Webhook\Github\V1\DeploymentsController', [
        'only' => [
            'store',
        ],
        'parameters' => [
            'projects' => 'project',
            'deployments' => 'deployment',
        ],
    ]);
});
