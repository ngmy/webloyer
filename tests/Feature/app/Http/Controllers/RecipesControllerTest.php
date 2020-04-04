<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class RecipesControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    protected $mockRecipeRepository;

    protected $mockRecipeForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(\App\Http\Middleware\ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mockPartial(User::class);
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockRecipeRepository = $this->mock('App\Repositories\Recipe\RecipeInterface');
        $this->mockRecipeForm = $this->mock('App\Services\Form\Recipe\RecipeForm');
        $this->mockRecipeModel = $this->mockPartial(Recipe::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $recipes[] = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection)
            ->mock();
        $recipes[] = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection)
            ->mock();

        $perPage = 10;

        $this->mockRecipeRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Illuminate\Pagination\Paginator($recipes, $perPage));

        $this->get('recipes');

        $this->assertResponseOk();
        $this->assertViewHas('recipes');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->get('recipes/create');

        $this->assertResponseOk();
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockRecipeForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->post('recipes');

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
    {
        $this->mockRecipeForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockRecipeForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $this->post('recipes');

        $this->assertRedirectedToRoute('recipes.create');
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $recipe = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Illuminate\Database\Eloquent\Collection)
            ->mock();

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $this->get('recipes/1');

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('recipes/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $recipe = Factory::build(Recipe::class, [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $this->get('recipes/1/edit');

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->get('recipes/1/edit');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $recipe = Factory::build(Recipe::class, [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $this->mockRecipeForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put('recipes/1');

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $recipe = Factory::build(Recipe::class, [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $this->mockRecipeForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->mockRecipeForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $this->put('recipes/1');

        $this->assertRedirectedToRoute('recipes.edit', [$recipe]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->put('recipes/1');

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $recipe = Factory::build(Recipe::class, [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
            'created_at'  => new Carbon(),
            'updated_at'  => new Carbon(),
        ]);

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $this->mockRecipeRepository
            ->shouldReceive('delete')
            ->once();

        $this->delete('recipes/1');

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $this->delete('recipes/1');

        $this->assertResponseStatus(404);
    }
}
