<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\{
    Blueprint,
    ForeignKeyDefinition,
};
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('permission_id')->unsigned()->index();
            $table->bigInteger('role_id')->unsigned()->index();
            $table->timestamps();

            $foreignPermissionId = $table->foreign('permission_id');
            assert($foreignPermissionId instanceof ForeignKeyDefinition);
            $foreignPermissionId->references('id')->on('permissions')->onDelete('cascade');
            $foreignRoleId = $table->foreign('role_id');
            assert($foreignRoleId instanceof ForeignKeyDefinition);
            $foreignRoleId->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
