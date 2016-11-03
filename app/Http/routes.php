<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'WelcomeController@index');

    Route::controller('auth', 'Auth\AuthController', [
        'getRegister' => 'auth.register',
        'getLogin'    => 'auth.login',
    ]);

    Route::controller('password', 'Auth\PasswordController', [
        'getEmail' => 'password.email',
        'getReset' => 'password.reset',
    ]);

    Route::group([
        'protect_alias' => 'project',
    ], function () {
        Route::resource('projects', 'ProjectsController');
    });

    Route::group([
        'protect_alias' => 'deployment',
    ], function () {
        Route::resource('projects.deployments', 'DeploymentsController', [
            'only' => ['index', 'store', 'show']
        ]);
    });

    Route::group([
        'protect_alias' => 'recipe',
    ], function () {
        Route::resource('recipes', 'RecipesController');
    });

    Route::group([
        'protect_alias' => 'server',
    ], function () {
        Route::resource('servers', 'ServersController');
    });

    Route::group([
        'protect_alias' => 'user',
    ], function () {
        Route::get('users/{users}/password/change', [
            'as'   => 'users.password.change',
            'uses' => 'UsersController@changePassword',
        ]);
        Route::put('users/{users}/password', [
            'as'   => 'users.password.update',
            'uses' => 'UsersController@updatePassword'
        ]);
        Route::get('users/{users}/role/edit', [
            'as'   => 'users.role.edit',
            'uses' => 'UsersController@editRole',
        ]);
        Route::put('users/{users}/role', [
            'as'   => 'users.role.update',
            'uses' => 'UsersController@updateRole'
        ]);
        Route::get('users/{users}/api_token/edit', [
            'as'   => 'users.api_token.edit',
            'uses' => 'UsersController@editApiToken',
        ]);
        Route::put('users/{users}/api_token', [
            'as'   => 'users.api_token.regenerate',
            'uses' => 'UsersController@regenerateApiToken'
        ]);
        Route::resource('users', 'UsersController');
    });

    Route::group([
        'protect_alias' => 'setting'
    ], function () {
        Route::controller('settings', 'SettingsController', [
            'getEmail' => 'settings.email'
        ]);
    });
});

Route::group(['middleware' => 'api'], function () {
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
});
