<?php

use App\Providers\RouteServiceProvider;

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
    return redirect(RouteServiceProvider::HOME);
});

Route::namespace('\App\Http\Controllers')->group(function () {
    Auth::routes();
    Route::namespace('Auth')->group(function () {
        Route::get('register', function () {
            abort(404);
        })->name('register');
        Route::post('register', function () {
            abort(404);
        });
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.forgot');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    });
});

Route::group([
    'namespace' => 'Project',
    'protect_alias' => 'project',
], function () {
    Route::resource('projects', 'ProjectController');
});

Route::group([
    'namespace' => 'Deployment',
    'protect_alias' => 'deployment',
], function () {
    Route::resource('projects.deployments', 'DeploymentController')->only([
        'index',
        'store',
        'show',
    ]);
});

Route::group([
    'namespace' => 'Recipe',
    'protect_alias' => 'recipe',
], function () {
    Route::resource('recipes', 'RecipeController');
});

Route::group([
    'namespace' => 'Server',
    'protect_alias' => 'server',
], function () {
    Route::resource('servers', 'ServerController');
});

Route::group([
    'namespace' => 'User',
    'protect_alias' => 'user',
], function () {
    Route::get('users/{user}/password/change', 'UserController@changePassword')->name('users.password.change');
    Route::put('users/{user}/password', 'UserController@updatePassword')->name('users.password.update');
    Route::get('users/{user}/role/edit', 'UserController@editRole')->name('users.role.edit');
    Route::put('users/{user}/role', 'UserController@updateRole')->name('users.role.update');
    Route::get('users/{user}/api_token/edit', 'UserController@editApiToken')->name('users.api_token.edit');
    Route::put('users/{user}/api_token', 'UserController@regenerateApiToken')->name('users.api_token.regenerate');
    Route::resource('users', 'UserController');
});
