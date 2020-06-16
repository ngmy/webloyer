<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\{
    Blueprint,
    ForeignKeyDefinition,
};
use Illuminate\Support\Facades\Schema;

class CreateProjectRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_recipe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('recipe_id')->unsigned();
            $table->tinyInteger('recipe_order')->unsigned();

            $foreignProjectId = $table->foreign('project_id');
            assert($foreignProjectId instanceof ForeignKeyDefinition);
            $foreignProjectId
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
            $foreignRecipeId = $table->foreign('recipe_id');
            assert($foreignRecipeId instanceof ForeignKeyDefinition);
            $foreignRecipeId
                ->references('id')
                ->on('recipes')
                ->onDelete('cascade');

            $table->unique(['project_id', 'recipe_id', 'recipe_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_recipe');
    }
}
