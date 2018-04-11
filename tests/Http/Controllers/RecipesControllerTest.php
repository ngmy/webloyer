<?php

use App\Http\Middleware\ApplySettings;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
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

    public function test_Should_DisplayCreatePage_When_CreatePageIsRequested()
    {
        $this->get('recipes/create');

        $this->assertResponseOk();
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $this->recipeForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();

        $this->post('recipes');

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_RedirectToCreatePage_When_StoreProcessFails()
    {
        $this->recipeForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->recipeForm
            ->shouldReceive('errors')
            ->andReturn([])
            ->once();

        $this->post('recipes');

        $this->assertRedirectedToRoute('recipes.create');
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);

        $projectId = new ProjectId(1);
        $afferentProjectIds = [$projectId];

        $project = $this->mock(Project::class);
        $project->shouldReceive('projectId')->andReturn($projectId);
        $project->shouldReceive('name');

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($projectId->id())
            ->andReturn($project)
            ->once();

        $recipe->shouldReceive('afferentProjectIds')->andReturn($afferentProjectIds);
        $recipe->shouldReceive('name');
        $recipe->shouldReceive('description');
        $recipe->shouldReceive('body');
        $recipe->shouldReceive('afferentProjectsCount')->andReturn(count($afferentProjectIds));
        $recipe->shouldReceive('recipeId->id');

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn($recipe)
            ->once();

        $this->get("recipes/$recipeId");

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
        $this->assertViewHas('afferentProjects');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $projectId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($projectId)
            ->andReturn(null)
            ->once();

        $this->get("recipes/$projectId");

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('afferentProjectIds');
        $recipe->shouldReceive('name');
        $recipe->shouldReceive('description');
        $recipe->shouldReceive('body');
        $recipe->shouldReceive('afferentProjectsCount');
        $recipe->shouldReceive('recipeId->id');
        $recipe->shouldReceive('concurrencyVersion');

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn($recipe)
            ->once();

        $this->get("recipes/$recipeId/edit");

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->andReturn(null)
            ->once();

        $this->get("recipes/$recipeId/edit");

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('afferentProjectIds');
        $recipe->shouldReceive('name');
        $recipe->shouldReceive('description');
        $recipe->shouldReceive('body');
        $recipe->shouldReceive('afferentProjectsCount');
        $recipe->shouldReceive('recipeId->id');
        $recipe->shouldReceive('concurrencyVersion');

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn($recipe)
            ->once();

        $this->recipeForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put("recipes/$recipeId");

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('afferentProjectIds');
        $recipe->shouldReceive('name');
        $recipe->shouldReceive('description');
        $recipe->shouldReceive('body');
        $recipe->shouldReceive('afferentProjectsCount');
        $recipe->shouldReceive('recipeId->id')->andReturn($recipeId);
        $recipe->shouldReceive('concurrencyVersion');

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn($recipe)
            ->once();

        $this->recipeForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $this->recipeForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $this->put("recipes/$recipeId");

        $this->assertRedirectedToRoute('recipes.edit', [$recipeId]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->andReturn(null)
            ->once();

        $this->put("recipes/$recipeId");

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('afferentProjectIds');
        $recipe->shouldReceive('name');
        $recipe->shouldReceive('description');
        $recipe->shouldReceive('body');
        $recipe->shouldReceive('afferentProjectsCount');
        $recipe->shouldReceive('recipeId->id');
        $recipe->shouldReceive('concurrencyVersion');

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn($recipe)
            ->once();

        $this->recipeService
            ->shouldReceive('removeRecipe')
            ->once();

        $this->delete("recipes/$recipeId");

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->andReturn(null)
            ->once();

        $this->delete("recipes/$recipeId");

        $this->assertResponseStatus(404);
    }
}
