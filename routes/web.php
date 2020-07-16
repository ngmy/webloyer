<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

Auth::routes();
// Registration Routes...
Route::get('register', [
    'as'   => 'register',
    'uses' => function () {
        abort(404);
    }
]);
Route::post('register', function () {
    abort(404);
});
Route::get('password/reset', [
    'as'   => 'password.forgot',
    'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::get('password/reset/{token}', [
    'as'   => 'password.reset',
    'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

Route::group([
    'protect_alias' => 'project',
], function () {
    Route::resource('projects', 'ProjectsController', [
        'parameters' => [
            'projects' => 'project',
        ],
    ]);
});

Route::group([
    'protect_alias' => 'deployment',
], function () {
    Route::resource('projects.deployments', 'DeploymentsController', [
        'only' => [
            'index',
            'store',
            'show',
        ],
        'parameters' => [
            'projects' => 'project',
            'deployments' => 'deployment',
        ],
    ]);
});

Route::group([
    'protect_alias' => 'recipe',
], function () {
    Route::resource('recipes', 'RecipesController', [
        'parameters' => [
            'recipes' => 'recipe',
        ],
    ]);
});

Route::group([
    'protect_alias' => 'server',
], function () {
    Route::resource('servers', 'ServersController', [
        'parameters' => [
            'servers' => 'server',
        ],
    ]);
});

Route::group([
    'protect_alias' => 'user',
], function () {
    Route::get('users/{user}/password/change', [
        'as'   => 'users.password.change',
        'uses' => 'UsersController@changePassword',
    ]);
    Route::put('users/{user}/password', [
        'as'   => 'users.password.update',
        'uses' => 'UsersController@updatePassword'
    ]);
    Route::get('users/{user}/role/edit', [
        'as'   => 'users.role.edit',
        'uses' => 'UsersController@editRole',
    ]);
    Route::put('users/{user}/role', [
        'as'   => 'users.role.update',
        'uses' => 'UsersController@updateRole'
    ]);
    Route::get('users/{user}/api_token/edit', [
        'as'   => 'users.api_token.edit',
        'uses' => 'UsersController@editApiToken',
    ]);
    Route::put('users/{user}/api_token', [
        'as'   => 'users.api_token.regenerate',
        'uses' => 'UsersController@regenerateApiToken'
    ]);
    Route::resource('users', 'UsersController', [
        'parameters' => [
            'users' => 'user',
        ],
    ]);
});

Route::group([
    'protect_alias' => 'setting'
], function () {
    Route::get('settings/email', [
        'as'   => 'settings.email',
        'uses' => 'SettingsController@getEmail'
    ]);
    Route::post('settings/email', 'SettingsController@postEmail');
});
