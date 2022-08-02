<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\Github\V1\DeploymentsController as GithubApiDeploymentsController;
use App\Http\Controllers\Webhook\Bitbucket\V1\DeploymentsController as BitbucketApiDeploymentsController;
use App\Services\Api\JsonRpc;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {

    Route::rpc('v1/jsonrpc', [JsonRpc::class])
        ->name('rpc.jsonrpc')
        ->middleware('auth.jsonrpc');

    Route::group(['prefix' => 'webhook/github/v1'], function () {
        Route::post('projects/{project}/deployments',
            [
                GithubApiDeploymentsController::class,
                'store'
            ])->name('github.projects.deployments');
    });

    Route::group(['prefix' => 'webhook/bitbucket/v1'], function () {
        Route::post('projects/{project}/deployments',
            [
                BitbucketApiDeploymentsController::class,
                'store'
            ])->name('bitbucket.projects.deployments');
    });
});
