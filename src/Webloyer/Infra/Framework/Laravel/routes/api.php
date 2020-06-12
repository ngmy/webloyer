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

Route::prefix('v1')->namespace('V1')->group(function () {
    Route::group([
        'middleware' => ['auth:api', 'acl'],
        'namespace' => 'JsonRpc',
        'protect_alias' => 'deployment',
    ], function () {
        Route::post('jsonrpc', 'JsonRpcController');
    });
});
