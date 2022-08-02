<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\DeploymentsController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SettingsController;

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

Route::middleware(['web'])->group(function () {

    require __DIR__.'/auth.php';

    Route::get('/', function() {
        return redirect('projects');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('auth.register', 'getRegister');
        Route::get('auth.login', 'getLogin');
    });
    Route::controller(PasswordController::class)->group(function () {
        Route::get('password.email', 'getEmail');
        Route::get('password.reset', 'getReset');
    });
    Route::group([
        'protect_alias' => 'project'
    ], function () {
        Route::resource('projects', ProjectsController::class);
    });
    Route::group([
        'protect_alias' => 'deployment',
    ], function () {
        Route::get('projects/{project}/deployments/{deployment}/delete',
            [
                DeploymentsController::class,
                'delete'
            ])->name('projects.deployments.delete');
        Route::get('projects/{project}/deployments/{deployment}/show',
            [
                DeploymentsController::class,
                'show'
            ])->name('projects.deployments.show');
        Route::get('projects/{project}/deployments',
            [
                DeploymentsController::class,
                'index'
            ])->name('projects.deployments.index');
        Route::post('projects/{project}/deployments',
            [
                DeploymentsController::class,
                'store'
            ])->name('projects.deployments.store');
    });

    Route::group([
        'protect_alias' => 'recipe',
    ], function () {
        Route::resource('recipes', RecipesController::class);
    });

    Route::group([
        'protect_alias' => 'server',
    ], function () {
        Route::resource('servers', ServersController::class);
    });

    Route::group([
        'protect_alias' => 'user',
    ], function () {
        Route::get('users/{users}/password/change',
            [
                UsersController::class,
                'changePassword'
            ])->name('users.password.change');
        Route::put('users/{users}/password',
            [
                UsersController::class,
                'updatePassword'
            ])->name('users.password.update');
        Route::get('users/{users}/role/edit',
            [
                UsersController::class,
                'editRole'
            ])->name('users.role.edit');
        Route::put('users/{users}/role',
            [
                UsersController::class,
                'updateRole'
            ])->name('users.role.update');
        Route::get('users/{users}/api_token/edit',
            [
                UsersController::class,
                'editApiToken'
            ])->name('users.api_token.edit');
        Route::put('users/{users}/api_token',
            [
                UsersController::class,
                'regenerateApiToken'
            ])->name('users.api_token.regenerate');
        Route::resource('users', UsersController::class);
    });

    Route::group([
        'protect_alias' => 'setting'
    ], function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('settings/email',
                [
                    SettingsController::class,
                    'getEmail'
                ])->name('settings.email');
            Route::post('settings/email',
                [
                    SettingsController::class,
                    'postEmail'
                ])->name('settings.email.update');
        });
    });
});



