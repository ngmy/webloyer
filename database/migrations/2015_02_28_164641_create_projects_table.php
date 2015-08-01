<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('recipe_id')->unsigned();
			$table->string('stage')->nullable();
			$table->integer('server_id')->unsigned();
			$table->string('repository');
			$table->timestamps();

			$table->foreign('recipe_id')
				->references('id')
				->on('recipes');
			$table->foreign('server_id')
				->references('id')
				->on('servers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
