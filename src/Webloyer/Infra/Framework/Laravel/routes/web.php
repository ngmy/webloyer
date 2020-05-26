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
    Route::get('projects', 'IndexController')->name('projects.index');
    Route::get('projects/create', 'CreateController')->name('projects.create');
    Route::post('projects', 'StoreController')->name('projects.store');
    Route::get('projects/{project}', 'ShowController')->name('projects.show');
    Route::get('projects/{project}/edit', 'EditController')->name('projects.edit');
    Route::put('projects/{project}', 'UpdateController')->name('projects.update');
    Route::delete('projects/{project}', 'DestroyController')->name('projects.destroy');
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
    Route::get('servers', 'IndexController')->name('servers.index');
    Route::get('servers/create', 'CreateController')->name('servers.create');
    Route::post('servers', 'StoreController')->name('servers.store');
    Route::get('servers/{server}', 'ShowController')->name('servers.show');
    Route::get('servers/{server}/edit', 'EditController')->name('servers.edit');
    Route::put('servers/{server}', 'UpdateController')->name('servers.update');
    Route::delete('servers/{server}', 'DestroyController')->name('servers.destroy');
});

Route::group([
    'namespace' => 'User',
    'protect_alias' => 'user',
], function () {
    Route::get('users', 'IndexController')->name('users.index');
    Route::get('users/create', 'CreateController')->name('users.create');
    Route::post('users', 'StoreController')->name('users.store');
    Route::get('users/{user}', 'ShowController')->name('users.show');
    Route::get('users/{user}/edit', 'EditController')->name('users.edit');
    Route::put('users/{user}', 'UpdateController')->name('users.update');
    Route::delete('users/{user}', 'DestroyController')->name('users.destroy');
    Route::get('users/{user}/password/change', 'ChangePasswordController')->name('users.password.change');
    Route::put('users/{user}/password', 'UpdatePasswordController')->name('users.password.update');
    Route::get('users/{user}/role/edit', 'EditRoleController')->name('users.role.edit');
    Route::put('users/{user}/role', 'UpdateRoleController')->name('users.role.update');
    Route::get('users/{user}/api_token/edit', 'EditApiTokenController')->name('users.api_token.edit');
    Route::put('users/{user}/api_token', 'RegenerateApiTokenController')->name('users.api_token.regenerate');
});
