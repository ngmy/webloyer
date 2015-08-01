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
	'getLogin' => 'auth.login',
]);

Route::controller('password', 'Auth\PasswordController', [
	'getEmail' => 'password.email',
	'getReset' => 'password.reset',
]);

Route::resource('projects', 'ProjectsController');
Route::resource('projects.deployments', 'DeploymentsController', [
	'expect' => ['index', 'store', 'show']
]);

Route::resource('recipes', 'RecipesController');

Route::resource('servers', 'ServersController');
