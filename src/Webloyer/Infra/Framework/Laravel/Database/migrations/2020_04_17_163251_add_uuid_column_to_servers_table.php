<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidColumnToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('uuid', 36)->after('id')->unique()->nullable();
        });

        // HACK: for "SQLSTATE[HY000]: General error: 1 Cannot add a NOT NULL column with default value NULL"
        Schema::table('servers', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
