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

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth.register', 'Auth\AuthController@getRegister');
Route::get('auth.login', 'Auth\AuthController@getLogin');

Route::get('password.email', 'Auth\PasswordController@getEmail');
Route::get('password.reset', 'Auth\PasswordController@getReset');

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
    Route::get('settings.email', 'SettingsController@getEmail');
});
