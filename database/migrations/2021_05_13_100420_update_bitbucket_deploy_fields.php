<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateBitbucketDeployFields
 */
class UpdateBitbucketDeployFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deployments', function(Blueprint $table)
        {
            $table->string('commit_hash')->nullable();
        });
        Schema::table('projects', function(Blueprint$table)
        {
            $table->dropColumn('bitbucket_webhook_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deployments', function(Blueprint$table)
        {
            $table->dropColumn('commit_hash');
        });
        Schema::table('projects', function(Blueprint$table)
        {
            $table->integer('bitbucket_webhook_user_id')->unsigned()->nullable();
        });
    }
}
