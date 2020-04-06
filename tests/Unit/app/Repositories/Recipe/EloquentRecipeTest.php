<?php

namespace Tests\Unit\app\Repositories\Recipe;

use App\Models\Recipe;
use App\Repositories\Recipe\EloquentRecipe;
use Tests\TestCase;

class EloquentRecipeTest extends TestCase
{
    protected $useDatabase = true;

    /** @var EloquentRecipe */
    private $sut;

    public function test_Should_GetRecipeById()
    {
        $recipe = factory(Recipe::class)->create();

        $actual = $this->sut->byId($recipe->id);

        $this->assertTrue($recipe->is($actual));
    }

    public function test_Should_GetRecipesByPage()
    {
        $recipes = factory(Recipe::class, 5)->create();

        $actual = $this->sut->byPage();

        $this->assertCount(5, $actual->items());
    }

    public function test_Should_CreateNewRecipe()
    {
        $actual = $this->sut->create([
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);

        $this->assertDatabaseHas('recipes', $actual->toArray());
    }

    public function test_Should_UpdateExistingRecipe()
    {
        $recipe = factory(Recipe::class)->create();

        $this->sut->update([
            'id'          => $recipe->id,
            'name'        => 'Recipe 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);

        $this->assertDatabaseHas('recipes', [
            'id'          => $recipe->id,
            'name'        => 'Recipe 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);
    }

    public function test_Should_DeleteExistingRecipe()
    {
        $recipe = factory(Recipe::class)->create();

        $this->sut->delete($recipe->id);

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    /**
     * @before
     */
    public function setUpSut(): void
    {
        $this->sut = new EloquentRecipe(new Recipe());
    }
}
