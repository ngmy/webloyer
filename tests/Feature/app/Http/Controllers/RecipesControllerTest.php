<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Recipe\RecipeInterface;
use App\Services\Form\Recipe\RecipeForm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\TestCase;

class RecipesControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockRecipeRepository;

    protected $mockRecipeForm;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockRecipeRepository = $this->mock(RecipeInterface::class);
        $this->mockRecipeForm = $this->mock(RecipeForm::class);
        $this->mockRecipeModel = $this->partialMock(Recipe::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $recipes[] = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();
        $recipes[] = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();

        $perPage = 10;

        $this->mockRecipeRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($recipes, $perPage));

        $response = $this->get('recipes');

        $response->assertStatus(200);
        $response->assertViewHas('recipes');
    }

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $response = $this->get('recipes/create');

        $response->assertStatus(200);
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->mockRecipeForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('recipes');

        $response->assertRedirect('recipes');
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

        $response = $this->post('recipes');

        $response->assertRedirect('recipes/create');
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $recipe = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $response = $this->get('recipes/1');

        $response->assertStatus(200);
        $response->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('recipes/1');

        $response->assertStatus(404);
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

        $response = $this->get('recipes/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('recipes/1/edit');

        $response->assertStatus(404);
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

        $response = $this->put('recipes/1');

        $response->assertRedirect('recipes');
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

        $response = $this->put('recipes/1');

        $response->assertRedirect('recipes/1/edit');
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('recipes/1');

        $response->assertStatus(404);
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

        $response = $this->delete('recipes/1');

        $response->assertRedirect('recipes');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('recipes/1');

        $response->assertStatus(404);
    }
}
