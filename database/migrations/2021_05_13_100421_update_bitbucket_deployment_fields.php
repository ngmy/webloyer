<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateBitbucketDeploymentFields
 */
class UpdateBitbucketDeploymentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deployments', function(Blueprint$table)
        {
            $table->string('user_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deployments', function(Blueprint $table)
        {
            $table->dropColumn('user_email');
        });
    }
}
