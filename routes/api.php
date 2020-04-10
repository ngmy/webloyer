<?php

use App\Services\Api\JsonRpc;
use App\Services\Api\Middleware\Authenticate;
use Illuminate\Http\Request;
use JsonRPC\Server;

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

Route::group(['prefix' => 'v1'], function () {
    Route::post('jsonrpc', function (Request $request) {
        $server = new Server();
        $middlewareHandler = $server->getMiddlewareHandler();
        $middlewareHandler->withMiddleware(new Authenticate($request));
        $procedureHandler = $server->getProcedureHandler();
        $procedureHandler->withObject(app()->make(JsonRpc::class));
        return $server->execute();
    });
});
