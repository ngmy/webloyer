<?php

use Webloyer\Infra\Framework\Laravel\App\Providers\WebloyerRouteServiceProvider as RouteServiceProvider;

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
    Route::get('recipes', 'IndexController')->name('recipes.index');
    Route::get('recipes/create', 'CreateController')->name('recipes.create');
    Route::post('recipes', 'StoreController')->name('recipes.store');
    Route::get('recipes/{recipe}', 'ShowController')->name('recipes.show');
    Route::get('recipes/{recipe}/edit', 'EditController')->name('recipes.edit');
    Route::put('recipes/{recipe}', 'UpdateController')->name('recipes.update');
    Route::delete('recipes/{recipe}', 'DestroyController')->name('recipes.destroy');
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
