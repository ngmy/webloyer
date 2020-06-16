<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\{
    Blueprint,
    ForeignKeyDefinition,
};
use Illuminate\Support\Facades\Schema;

class CreatePermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('permission_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();

            $foreignPermissionId = $table->foreign('permission_id');
            assert($foreignPermissionId instanceof ForeignKeyDefinition);
            $foreignPermissionId->references('id')->on('permissions')->onDelete('cascade');
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
        Schema::dropIfExists('permission_user');
    }
}
