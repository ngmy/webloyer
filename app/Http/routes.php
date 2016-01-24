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
        'expect' => ['index', 'store', 'show']
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
    Route::resource('users', 'UsersController');
});
