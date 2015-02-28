<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeploymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deployments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->integer('number')->unsigned;
			$table->string('task');
			$table->tinyInteger('status')->unsigned()->nullable();
			$table->text('message')->nullable();
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('project_id')
				->references('id')
				->on('projects')
				->onDelete('cascade');

			$table->unique(['project_id', 'number']);
		});

		$sql = <<<SQL
CREATE TRIGGER trigger_deployments_before_insert BEFORE INSERT ON deployments
  FOR EACH ROW
  BEGIN
    SET NEW.number = (SELECT IFNULL(MAX(number), 0) + 1 FROM deployments WHERE project_id = NEW.project_id);
  END;
SQL;

		DB::unprepared($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('deployments');
	}

}
