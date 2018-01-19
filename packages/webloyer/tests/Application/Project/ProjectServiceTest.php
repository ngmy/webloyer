<?php

namespace Ngmy\Webloyer\Webloyer\Application\Project;

use Mockery;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use TestCase;
use Tests\Helpers\MockeryHelper;

class ProjectServiceTest extends TestCase
{
    use MockeryHelper;

    private $projectService;

    private $projectRepository;

    private $inputForGetProjectsByPage = [
        'page'    => 1,
        'perPage' => 10,
    ];

    private $inputForSaveProject = [
        'projectId'                    => 1,
        'name'                         => '',
        'recipeIds'                    => [1],
        'serverId'                     => 1,
        'repositoryUrl'                => '',
        'stage'                        => '',
        'deployPath'                   => '',
        'emailNotificationRecipient'   => '',
        'daysToKeepDeployments'        => 1,
        'maxNumberOfDeploymentsToKeep' => 1,
        'keepLastDeployment'           => KeepLastDeployment::ENUM['on'],
        'githubWebhookSecret'          => '',
        'githubWebhookExecuteUserId'   => 1,
        'concurrencyVersion'           => '',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = $this->mock(ProjectRepositoryInterface::class);
        $this->projectService = new ProjectService($this->projectRepository);
    }

    public function test_Should_GetAllProjects()
    {
        $expectedResult = true;
        $this->projectRepository
            ->shouldReceive('allProjects')
            ->withNoArgs()
            ->once()
            ->andReturn($expectedResult);

        $actualResult = $this->projectService->getAllProjects();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetProjectsByPage_When_PageAndPerPageIsNotSpecified()
    {
        $this->checkGetProjectsByPage(null, null, 1, 10);
    }

    public function test_Should_GetProjectsByPage_When_PageAndPerPageIsSpecified()
    {
        $this->checkGetProjectsByPage(2, 20, 2, 20);
    }

    public function test_Should_GetProjectById()
    {
        $projectId = 1;
        $expectedResult = true;
        $this->projectRepository
            ->shouldReceive('projectOfId')
            ->with(Mockery::on(function ($arg) use ($projectId) {
                return $arg == new ProjectId($projectId);
            }))
            ->once()
            ->andReturn($expectedResult);

        $actualResult = $this->projectService->getProjectById($projectId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SaveProject_When_ProjectIdIsNull()
    {
        $this->checkSaveProject(true, false);
    }

    public function test_Should_SaveProject_When_ProjectIdIsNotNullAndProjectExists()
    {
        $this->checkSaveProject(true, true);
    }

    public function test_Should_SaveProject_When_ProjectIdIsNotNullAndProjectNotExists()
    {
        $this->checkSaveProject(true, false);
    }

    public function test_Should_RemoveProject()
    {
        $projectId = 1;
        $project = $this->mock(Project::class);
        $this->projectRepository
            ->shouldReceive('projectOfId')
            ->with(Mockery::on(function ($arg) use ($projectId) {
                return $arg == new ProjectId($projectId);
            }))
            ->once()
            ->andReturn($project);
        $this->projectRepository
            ->shouldReceive('remove')
            ->with($project)
            ->once();

        $this->projectService->removeProject($projectId);

        $this->assertTrue(true);
    }

    private function checkGetProjectsByPage($inputPage, $inputPerPage, $expectedPage, $expectedPerPage)
    {
        $this->inputForGetProjectsByPage['page'] = $inputPage;
        $this->inputForGetProjectsByPage['perPage'] = $inputPerPage;

        $expectedResult = true;
        $this->projectRepository
            ->shouldReceive('projectsOfPage')
            ->with($expectedPage, $expectedPerPage)
            ->once()
            ->andReturn($expectedResult);

        extract($this->inputForGetProjectsByPage);

        if (isset($page) && isset($perPage)) {
            $actualResult = $this->projectService->getProjectsByPage($page, $perPage);
        } elseif (isset($page)) {
            $actualResult = $this->projectService->getProjectsByPage($page);
        } else {
            $actualResult = $this->projectService->getProjectsByPage();
        }

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function checkSaveProject($isNullInputProjectId, $existsProject)
    {
        if ($isNullInputProjectId) {
            $this->inputForSaveProject['projectId'] = null;
        } else {
            $this->inputForSaveProject['projectId'] = 1;
            if ($existsProject) {
                $project = $this->mock(Project::class);
                $project
                    ->shouldReceive('failWhenConcurrencyViolation')
                    ->with($this->inputForSaveProject['concurrencyVersion'])
                    ->once();
            } else {
                $project = null;
            }
            $this->projectRepository
                ->shouldReceive('projectOfId')
                ->with(Mockery::on(function ($arg) {
                    return $arg == new ProjectId($this->inputForSaveProject['projectId']);
                }))
                ->once()
                ->andReturn($project);
        }

        $this->projectRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveProject);
                $project = new Project(
                    new ProjectId($projectId),
                    $name,
                    array_map(function ($recipeId) {
                        return new RecipeId($recipeId);
                    }, $recipeIds),
                    new ServerId($serverId),
                    $repositoryUrl,
                    $stage,
                    new ProjectAttribute($deployPath),
                    $emailNotificationRecipient,
                    $daysToKeepDeployments,
                    $maxNumberOfDeploymentsToKeep,
                    new KeepLastDeployment($keepLastDeployment),
                    $githubWebhookSecret,
                    new UserId($githubWebhookExecuteUserId),
                    null,
                    null
                );
                return $arg == $project;
            }))
            ->once();

        extract($this->inputForSaveProject);

        $this->projectService->saveProject(
            $projectId,
            $name,
            $recipeIds,
            $serverId,
            $repositoryUrl,
            $stage,
            $deployPath,
            $emailNotificationRecipient,
            $daysToKeepDeployments,
            $maxNumberOfDeploymentsToKeep,
            $keepLastDeployment,
            $githubWebhookSecret,
            $githubWebhookExecuteUserId,
            $concurrencyVersion
        );

        $this->assertTrue(true);
    }
}
