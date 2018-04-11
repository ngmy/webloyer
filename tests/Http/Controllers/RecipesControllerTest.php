<?php

use App\Http\Middleware\ApplySettings;
use Carbon\Carbon;
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
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
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
        $recipe = $this->createRecipe();
        $recipes = new Collection([
            $recipe,
        ]);
        $page = 1;
        $perPage = 10;

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
        $recipe = $this->createRecipe([
            'afferentProjectIds' => [1, 2],
        ]);

        foreach ($recipe->afferentProjectIds() as $afferentProjectId) {
            $project = $this->createProject([
                'projectId' => $afferentProjectId->id(),
            ]);
            $this->projectService
                ->shouldReceive('getProjectById')
                ->with($project->projectId()->id())
                ->andReturn($project)
                ->once();
        }

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipe->recipeId()->id())
            ->andReturn($recipe)
            ->once();

        $this->get("recipes/{$recipe->recipeId()->id()}");

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
        $this->assertViewHas('afferentProjects');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn(null)
            ->once();

        $this->get("recipes/$recipeId");

        $this->assertResponseStatus(404);
    }

    public function test_Should_DisplayEditPage_When_EditPageIsRequestedAndResourceIsFound()
    {
        $recipe = $this->createRecipe();

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipe->recipeId()->id())
            ->andReturn($recipe)
            ->once();

        $this->get("recipes/{$recipe->recipeId()->id()}/edit");

        $this->assertResponseOk();
        $this->assertViewHas('recipe');
    }

    public function test_Should_DisplayNotFoundPage_When_EditPageIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn(null)
            ->once();

        $this->get("recipes/$recipeId/edit");

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_UpdateProcessSucceeds()
    {
        $recipe = $this->createRecipe();

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipe->recipeId()->id())
            ->andReturn($recipe)
            ->once();

        $this->recipeForm
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $this->put("recipes/{$recipe->recipeId()->id()}");

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_RedirectToEditPage_When_UpdateProcessFails()
    {
        $recipe = $this->createRecipe();

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipe->recipeId()->id())
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

        $this->put("recipes/{$recipe->recipeId()->id()}");

        $this->assertRedirectedToRoute('recipes.edit', [$recipe->recipeId()->id()]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayNotFoundPage_When_UpdateProcessIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn(null)
            ->once();

        $this->put("recipes/$recipeId");

        $this->assertResponseStatus(404);
    }

    public function test_Should_RedirectToIndexPage_When_DestroyProcessIsRequestedAndDestroyProcessSucceeds()
    {
        $recipe = $this->createRecipe();

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipe->recipeId()->id())
            ->andReturn($recipe)
            ->once();

        $this->recipeService
            ->shouldReceive('removeRecipe')
            ->once();

        $this->delete("recipes/{$recipe->recipeId()->id()}");

        $this->assertRedirectedToRoute('recipes.index');
    }

    public function test_Should_DisplayNotFoundPage_When_DestroyProcessIsRequestedAndResourceIsNotFound()
    {
        $recipeId = 1;

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($recipeId)
            ->andReturn(null)
            ->once();

        $this->delete("recipes/$recipeId");

        $this->assertResponseStatus(404);
    }

    private function createRecipe(array $params = [])
    {
        $recipeId = 1;
        $name = '';
        $description = '';
        $body = '';
        $afferentProjectIds = [1];
        $createdAt = null;
        $updatedAt = null;
        $concurrencyVersion = '';

        extract($params);

        $recipe = $this->mock(Recipe::class);

        $recipe->shouldReceive('recipeId')->andReturn(new RecipeId($recipeId));
        $recipe->shouldReceive('name')->andReturn($name);
        $recipe->shouldReceive('description')->andReturn($description);
        $recipe->shouldReceive('body')->andReturn($body);
        $recipe->shouldReceive('afferentProjectIds')->andReturn(array_map(function ($afferentProjectId) {
            return new ProjectId($afferentProjectId);
        }, $afferentProjectIds));
        $recipe->shouldReceive('afferentProjectsCount')->andReturn(count($afferentProjectIds));
        $recipe->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $recipe->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));
        $recipe->shouldReceive('concurrencyVersion')->andReturn($concurrencyVersion);

        return $recipe;
    }

    private function createProject(array $params)
    {
        $projectId = 1;
        $name = '';

        extract($params);

        $project = $this->mock(Project::class);

        $project->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $project->shouldReceive('name')->andReturn($name);

        return $project;
    }
}
