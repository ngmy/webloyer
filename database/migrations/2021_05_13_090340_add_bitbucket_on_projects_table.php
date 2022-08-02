<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddBitbucketOnProjectsTable
 */
class AddBitbucketOnProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function(Blueprint $table)
        {
            $table->string('bitbucket_webhook_secret')->nullable();
            $table->integer('bitbucket_webhook_user_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function(Blueprint $table)
        {
            $table->dropColumn('bitbucket_webhook_secret');
            $table->dropColumn('bitbucket_webhook_user_id');
        });
    }
}
