<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\{
    Blueprint,
    ForeignKeyDefinition,
};
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inherit_id')->unsigned()->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->timestamps();

            $foreignInheritId = $table->foreign('inherit_id');
            assert($foreignInheritId instanceof ForeignKeyDefinition);
            $foreignInheritId->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
