<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateProjectsTable
 */
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
            $table->id();
            $table->string('name');
            $table->string('stage')->nullable();
            $table->bigInteger('server_id')->unsigned();
            $table->string('repository');
            $table->string('email_notification_recipient')->nullable();
            $table->text('attributes');
            $table->integer('days_to_keep_deployments')->unsigned()->nullable();
            $table->integer('max_number_of_deployments_to_keep')->unsigned()->nullable();
            $table->tinyInteger('keep_last_deployment')->unsigned();
            $table->string('github_webhook_secret')->nullable();
            $table->bigInteger('github_webhook_user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('server_id')
                ->references('id')
                ->on('servers');
            $table->foreign('github_webhook_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
