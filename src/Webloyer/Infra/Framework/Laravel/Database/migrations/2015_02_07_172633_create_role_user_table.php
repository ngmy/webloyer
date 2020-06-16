<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\{
    Blueprint,
    ForeignKeyDefinition,
};
use Illuminate\Support\Facades\Schema;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();

            $foreignRoleId = $table->foreign('role_id');
            assert($foreignRoleId instanceof ForeignKeyDefinition);
            $foreignRoleId->references('id')->on('roles')->onDelete('cascade');
            $foreignUserId = $table->foreign('user_id');
            assert($foreignUserId instanceof ForeignKeyDefinition);
            $foreignUserId->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}
