<?php

declare(strict_types=1);

namespace Webloyer\Infra\Projection\File;

use Storage;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project;
use Webloyer\Domain\Model\Recipe;
use Webloyer\Domain\Model\Server;

class DeploymentProjection implements Deployment\DeploymentProjection
{
    /** @var Project\ProjectRepository */
    private $projectRepository;
    /** @var Recipe\RecipeRepository */
    private $recipeRepository;
    /** @var Server\ServerRepository */
    private $serverRepository;

    /**
     * @param Project\ProjectRepository $projectRepository
     * @param Recipe\RecipeRepository   $projectRepository
     * @param Server\ServerRepository   $projectRepository
     * @return void
     */
    public function __construct(
        Project\ProjectRepositoryt $projectRepository,
        Recipe\RecipeRepository $recipeRepository,
        Server\ServerRepository $serverRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->recipeRepository = $recipeRepository;
        $this->serverRepository = $serverRepository;
    }

    /**
     * @param Deployment\Deployment $deployment
     * @return void
     */
    public function projectDeploymentWasCreated(Deployment\Deployment $deployment): void
    {
        $project = $this->projectRepository->findById(new Project\ProjectId($deployment->projecId()));
        $recipes = array_map(function (string $recipeId): Recipe\Recipe {
            return $this->recipeRepository->findById(new Recipe\RecipeId($recipeId));
        }, $deployment->recipeIds());
        $server = $this->serverRepository->findById(new Server\ServerId($deployment->serverId()));

        // Create temporary recipe files
        $i = 1;
        $recipeFiles = array_map(function (Recipe\Recipe $recipe) use($project, $deployment): void {
            $recipeFileName = sprintf('server_%s_%s_%s.php', $project->id(), $deployment->number(), $i++);
            $this->createFile($recipeFileName, $recipe->body());
        }, $recipes->toArray());

        // Create the temporary server file
        $serverFileName = sprintf('server_%s_%s_%s.yaml', $project->id(), $deployment->number());
        $this->createFile($serverFileName, $server->body());

        // Create the temporary deployer file
        $contents[] = '<?php';
        $contents[] = 'namespace Deployer;';
        foreach ($recipeFiles as $recipeFile) {
            $contents[] = "require '" . $recipeFile->getPath() . "';";
        }
        $contents[] = "set('default_stage', '" . $project->stage() . "');";
        $contents[] = "set('repository', '" . $project->repository() . "');";
        $contents[] = "serverList('" . $serverFile->getPath() . "');";
        $deployerFileName = sprintf('deployer_%s_%s.php', $project->id(), $deployment->number());
        $this->createFile($deployerFileName, implode(PHP_EOL, $contents));
    }

    public function createFile(string $fileName, string $contents): void
    {
        Storage::disk('local')->put($fileName, $contents);
    }
}
