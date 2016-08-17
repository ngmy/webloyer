<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('stage')->nullable();
            $table->integer('server_id')->unsigned();
            $table->string('repository');
            $table->string('email_notification_recipient')->nullable();
            $table->text('attributes');
            $table->integer('days_to_keep_deployments')->unsigned()->nullable();
            $table->integer('max_number_of_deployments_to_keep')->unsigned()->nullable();
            $table->tinyInteger('keep_last_deployment')->unsigned();
            $table->timestamps();

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
