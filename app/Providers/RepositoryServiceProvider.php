<?php namespace App\Providers;

use App\Models\Project;
use App\Models\Deployment;
use App\Models\MaxDeployment;
use App\Repositories\Project\EloquentProject;
use App\Repositories\Deployment\EloquentDeployment;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('App\Repositories\Project\ProjectInterface', function ($app)
		{
			return new EloquentProject(
				new Project,
				new MaxDeployment
			);
		});

		$this->app->bind('App\Repositories\Deployment\DeploymentInterface', function ($app)
		{
			return new EloquentDeployment(new Deployment);
		});
	}

}
