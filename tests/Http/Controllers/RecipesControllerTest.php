<?php

use App\Http\Middleware\ApplySettings;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\Helpers\MockeryHelper;

class RecipesControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $recipeForm;

    private $recipeService;

    private $projectService;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware);

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $this->auth($user);

        $this->recipeForm = $this->mock(RecipeForm::class);
        $this->recipeService = $this->mock(RecipeService::class);
        $this->projectService = $this->mock(ProjectService::class);

        $this->app->instance(RecipeForm::class, $this->recipeForm);
        $this->app->instance(RecipeService::class, $this->recipeService);
        $this->app->instance(ProjectService::class, $this->projectService);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $recipe = $this->mock(Recipe::class);
        $recipes = new Collection([
            $recipe,
        ]);
        $page = 1;
        $perPage = 10;

        $recipe->shouldReceive('name');
        $recipe->shouldReceive('afferentProjectsCount');
        $recipe->shouldReceive('createdAt');
        $recipe->shouldReceive('updatedAt');
        $recipe->shouldReceive('recipeId->id');

        $this->recipeService
            ->shouldReceive('getRecipesByPage')
            ->with($page, $perPage)
            ->andReturn(
                new LengthAwarePaginator(
                    $recipes,
                    $recipes->count(),
                    $perPage,
                    $page,
                    [
                        'path' => Paginator::resolveCurrentPath(),
                    ]
                )
            )
            ->once();

        $this->get('recipes');

        $this->assertResponseOk();
        $this->assertViewHas('recipes');
    }

//    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
//    {
//        $this->get('recipes/create');
//
//        $this->assertResponseOk();
//    }
//
//    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
//    {
//        $this->recipeForm
//            ->shouldReceive('save')
//            ->once()
//            ->andReturn(true);
//
//        $this->post('recipes');
//
//        $this->assertRedirectedToRoute('recipes.index');
//    }
//
//    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
//    {
//        $this->recipeForm
//            ->shouldReceive('save')
//            ->once()
//            ->andReturn(false);
//
//        $this->recipeForm
//            ->shouldReceive('errors')
//            ->once()
//            ->andReturn([]);
//
//        $this->post('recipes');
//
//        $this->assertRedirectedToRoute('recipes.create');
//        $this->assertSessionHasErrors();
//    }
//
//    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
//    {
//        $recipe = $this->recipe
//            ->shouldReceive('getProjects')
//            ->once()
//            ->andReturn(new Illuminate\Database\Eloquent\Collection)
//            ->mock();
//
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn($recipe);
//
//        $this->get('recipes/1');
//
//        $this->assertResponseOk();
//        $this->assertViewHas('recipe');
//    }
//
//    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
//    {
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn(null);
//
//        $this->get('recipes/1');
//
//        $this->assertResponseStatus(404);
//    }
//
//    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
//    {
//        $recipe = Factory::build('App\Models\Recipe', [
//            'id'          => 1,
//            'name'        => 'Recipe 1',
//            'description' => '',
//            'body'        => '',
//            'created_at'  => new Carbon\Carbon,
//            'updated_at'  => new Carbon\Carbon,
//        ]);
//
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn($recipe);
//
//        $this->get('recipes/1/edit');
//
//        $this->assertResponseOk();
//        $this->assertViewHas('recipe');
//    }
//
//    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
//    {
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn(null);
//
//        $this->get('recipes/1/edit');
//
//        $this->assertResponseStatus(404);
//    }
//
//    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
//    {
//        $recipe = Factory::build('App\Models\Recipe', [
//            'id'          => 1,
//            'name'        => 'Recipe 1',
//            'description' => '',
//            'body'        => '',
//            'created_at'  => new Carbon\Carbon,
//            'updated_at'  => new Carbon\Carbon,
//        ]);
//
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn($recipe);
//
//        $this->recipeForm
//            ->shouldReceive('update')
//            ->once()
//            ->andReturn(true);
//
//        $this->put('recipes/1');
//
//        $this->assertRedirectedToRoute('recipes.index');
//    }
//
//    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
//    {
//        $recipe = Factory::build('App\Models\Recipe', [
//            'id'          => 1,
//            'name'        => 'Recipe 1',
//            'description' => '',
//            'body'        => '',
//            'created_at'  => new Carbon\Carbon,
//            'updated_at'  => new Carbon\Carbon,
//        ]);
//
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn($recipe);
//
//        $this->recipeForm
//            ->shouldReceive('update')
//            ->once()
//            ->andReturn(false);
//
//        $this->recipeForm
//            ->shouldReceive('errors')
//            ->once()
//            ->andReturn([]);
//
//        $this->put('recipes/1');
//
//        $this->assertRedirectedToRoute('recipes.edit', [$recipe]);
//        $this->assertSessionHasErrors();
//    }
//
//    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
//    {
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn(null);
//
//        $this->put('recipes/1');
//
//        $this->assertResponseStatus(404);
//    }
//
//    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
//    {
//        $recipe = Factory::build('App\Models\Recipe', [
//            'id'          => 1,
//            'name'        => 'Recipe 1',
//            'description' => '',
//            'body'        => '',
//            'created_at'  => new Carbon\Carbon,
//            'updated_at'  => new Carbon\Carbon,
//        ]);
//
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn($recipe);
//
//        $this->recipeRepository
//            ->shouldReceive('delete')
//            ->once();
//
//        $this->delete('recipes/1');
//
//        $this->assertRedirectedToRoute('recipes.index');
//    }
//
//    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
//    {
//        $this->recipeRepository
//            ->shouldReceive('byId')
//            ->once()
//            ->andReturn(null);
//
//        $this->delete('recipes/1');
//
//        $this->assertResponseStatus(404);
//    }
}
