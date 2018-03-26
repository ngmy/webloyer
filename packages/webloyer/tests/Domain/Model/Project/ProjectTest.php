<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use TestCase;

class ProjectTest extends TestCase
{
    public function test_Should_GetProjectId()
    {
        $expectedResult = new ProjectId(1);

        $project = $this->createProject([
            'projectId' => $expectedResult->id(),
        ]);

        $actualResult = $project->projectId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetName()
    {
        $expectedResult = 'some name';

        $project = $this->createProject([
            'name' => $expectedResult,
        ]);

        $actualResult = $project->name();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRecipeIds()
    {
        $expectedResult = [
            new RecipeId(1),
            new RecipeId(2),
        ];

        $project = $this->createProject([
            'recipeIds' => array_map(function ($recipeId) {
                return $recipeId->id();
            }, $expectedResult),
        ]);

        $actualResult = $project->recipeIds();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetServerId()
    {
        $expectedResult = new ServerId(1);

        $project = $this->createProject([
            'serverId' => $expectedResult->id(),
        ]);

        $actualResult = $project->serverId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRepositoryUrl()
    {
        $expectedResult = 'some repository url';

        $project = $this->createProject([
            'repositoryUrl' => $expectedResult,
        ]);

        $actualResult = $project->repositoryUrl();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetStage()
    {
        $expectedResult = 'some stage';

        $project = $this->createProject([
            'stage' => $expectedResult,
        ]);

        $actualResult = $project->stage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAttribute()
    {
        $attribute = [
            'deployPath' => 'some deploy path',
        ];
        $expectedResult = new ProjectAttribute($attribute['deployPath']);

        $project = $this->createProject([
            'attribute' => $attribute,
        ]);

        $actualResult = $project->attribute();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetEmailNotificationRecipient()
    {
        $expectedResult = 'some email notification recipient';

        $project = $this->createProject([
            'emailNotificationRecipient' => $expectedResult,
        ]);

        $actualResult = $project->emailNotificationRecipient();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDaysToKeepDeployments()
    {
        $expectedResult = 2;

        $project = $this->createProject([
            'daysToKeepDeployments' => $expectedResult,
        ]);

        $actualResult = $project->daysToKeepDeployments();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetMaxNumberOfDeploymentsToKeep()
    {
        $expectedResult = 2;

        $project = $this->createProject([
            'maxNumberOfDeploymentsToKeep' => $expectedResult,
        ]);

        $actualResult = $project->maxNumberOfDeploymentsToKeep();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetKeepLastDeployment()
    {
        $expectedResult = new KeepLastDeployment(1);

        $project = $this->createProject([
            'keepLastDeployment' => $expectedResult->value(),
        ]);

        $actualResult = $project->keepLastDeployment();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetGithubWebhookSecret()
    {
        $expectedResult = 'some github webhook secret';

        $project = $this->createProject([
            'githubWebhookSecret' => $expectedResult,
        ]);

        $actualResult = $project->githubWebhookSecret();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetGithubWebhookExecuteUserId()
    {
        $expectedResult = new UserId(1);

        $project = $this->createProject([
            'githubWebhookExecuteUserId' => $expectedResult->id(),
        ]);

        $actualResult = $project->githubWebhookExecuteUserId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetCreatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $project = $this->createProject([
            'createdAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $project->createdAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUpdatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $project = $this->createProject([
            'updatedAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $project->updatedAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProject(),
            $this->createProject(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProject(),
            $this->createProject([
                'projectId' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createProject(array $params = [])
    {
        $projectId = 1;
        $name = '';
        $recipeIds = [];
        $serverId = '';
        $repositoryUrl = '';
        $stage = '';
        $attribute = [
            'deployPath' => ''
        ];
        $emailNotificationRecipient = '';
        $daysToKeepDeployments = 1;
        $maxNumberOfDeploymentsToKeep = 1;
        $keepLastDeployment = 0;
        $githubWebhookSecret = '';
        $githubWebhookExecuteUserId = null;
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new Project(
            new ProjectId($projectId),
            $name,
            array_map(function ($recipeId) {
                return new RecipeId($recipeId);
            }, $recipeIds),
            new ServerId($serverId),
            $repositoryUrl,
            $stage,
            new ProjectAttribute($attribute['deployPath']),
            $emailNotificationRecipient,
            $daysToKeepDeployments,
            $maxNumberOfDeploymentsToKeep,
            new KeepLastDeployment($keepLastDeployment),
            $githubWebhookSecret,
            new UserId($githubWebhookExecuteUserId),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }
}
