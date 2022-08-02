<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateProjectRecipeTable
 */
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
            $table->id();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('recipe_id')->unsigned();
            $table->tinyInteger('recipe_order')->unsigned();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
            $table->foreign('recipe_id')
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
        Schema::drop('project_recipe');
    }
}
