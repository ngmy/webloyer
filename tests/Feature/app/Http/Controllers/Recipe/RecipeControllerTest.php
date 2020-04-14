<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Recipe;

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
use Tests\TestCase;

class RecipeControllerTest extends TestCase
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

    public function testShouldDisplayIndexPageWhenIndexPageIsRequested()
    {
        $recipe1 = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();
        $recipe1->id = 1;
        $recipe2 = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();
        $recipe2->id = 2;
        $recipes = [$recipe1, $recipe2];

        $perPage = 10;

        $this->mockRecipeRepository
            ->shouldReceive('byPage')
            ->once()
            ->andReturn(new Paginator($recipes, $perPage));

        $response = $this->get('recipes');

        $response->assertStatus(200);
        $response->assertViewHas('recipes');
    }

    public function testShouldDisplayCreatePageWhenCreatePageIsRequested()
    {
        $response = $this->get('recipes/create');

        $response->assertStatus(200);
    }

    public function testShouldRedirectToIndexPageWhenStoreProcessSucceeds()
    {
        $this->mockRecipeForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $response = $this->post('recipes');

        $response->assertRedirect('recipes');
    }

    public function testShouldRedirectToCreatePageWhenStoreProcessFails()
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

    public function testShouldDisplayShowPageWhenShowPageIsRequestedAndResourceIsFound()
    {
        $recipe = $this->mockRecipeModel
            ->shouldReceive('getProjects')
            ->once()
            ->andReturn(new Collection())
            ->mock();
        $recipe->id = 1;
        $recipe->name = '';

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $response = $this->get('recipes/1');

        $response->assertStatus(200);
        $response->assertViewHas('recipe');
    }

    public function testShouldDisplayNotFoundPageWhenShowPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('recipes/1');

        $response->assertStatus(404);
    }

    public function testShouldDisplayEditPageWhenEditPageIsRequestedAndResourceIsFound()
    {
        $recipe = factory(Recipe::class)->make([
            'id' => 1,
        ]);

        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($recipe);

        $response = $this->get('recipes/1/edit');

        $response->assertStatus(200);
        $response->assertViewHas('recipe');
    }

    public function testShouldDisplayNotFoundPageWhenEditPageIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->get('recipes/1/edit');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenUpdateProcessSucceeds()
    {
        $recipe = factory(Recipe::class)->make();

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

    public function testShouldRedirectToEditPageWhenUpdateProcessFails()
    {
        $recipe = factory(Recipe::class)->make([
            'id' => 1,
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

    public function testShouldDisplayNotFoundPageWhenUpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->put('recipes/1');

        $response->assertStatus(404);
    }

    public function testShouldRedirectToIndexPageWhenDestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $recipe = factory(Recipe::class)->make();

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

    public function testShouldDisplayNotFoundPageWhenDestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $this->mockRecipeRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn(null);

        $response = $this->delete('recipes/1');

        $response->assertStatus(404);
    }
}
