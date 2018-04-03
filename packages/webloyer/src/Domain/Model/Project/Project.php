<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\ConcurrencySafeTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;

class Project extends AbstractEntity
{
    use ConcurrencySafeTrait;

    private $projectId;

    private $name;

    private $recipeIds = [];

    private $serverId;

    private $repositoryUrl;

    private $stage;

    private $attribute;

    private $emailNotificationRecipient;

    private $daysToKeepDeployments;

    private $maxNumberOfDeploymentsToKeep;

    private $keepLastDeployment;

    private $githubWebhookSecret;

    private $githubWebhookExecuteUserId;

    private $createdAt;

    private $updatedAt;

    public function __construct(ProjectId $projectId, $name, array $recipeIds, ServerId $serverId, $repositoryUrl, $stage, ProjectAttribute $attribute, $emailNotificationRecipient, $daysToKeepDeployments, $maxNumberOfDeploymentsToKeep, KeepLastDeployment $keepLastDeployment, $githubWebhookSecret, UserId $githubWebhookExecuteUserId, Carbon $createdAt = null, Carbon $updatedAt = null)
    {
        $this->setProjectId($projectId);
        $this->setName($name);
        array_walk($recipeIds, [$this, 'addRecipeId']);
        $this->setServerId($serverId);
        $this->setRepositoryUrl($repositoryUrl);
        $this->setStage($stage);
        $this->setAttribute($attribute);
        $this->setEmailNotificationRecipient($emailNotificationRecipient);
        $this->setDaysToKeepDeployments($daysToKeepDeployments);
        $this->setMaxNumberOfDeploymentsToKeep($maxNumberOfDeploymentsToKeep);
        $this->setKeetLastDeployment($keepLastDeployment);
        $this->setGithubWebhookSecret($githubWebhookSecret);
        $this->setGithubWebhookExecuteUserId($githubWebhookExecuteUserId);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setConcurrencyVersion(md5(serialize($this)));
    }

    public function projectId()
    {
        return $this->projectId;
    }

    public function name()
    {
        return $this->name;
    }

    public function recipeIds()
    {
        return $this->recipeIds;
    }

    public function serverId()
    {
        return $this->serverId;
    }

    public function repositoryUrl()
    {
        return $this->repositoryUrl;
    }

    public function stage()
    {
        return $this->stage;
    }

    public function attribute()
    {
        return $this->attribute;
    }

    public function emailNotificationRecipient()
    {
        return $this->emailNotificationRecipient;
    }

    public function daysToKeepDeployments()
    {
        return $this->daysToKeepDeployments;
    }

    public function maxNumberOfDeploymentsToKeep()
    {
        return $this->maxNumberOfDeploymentsToKeep;
    }

    public function keepLastDeployment()
    {
        return $this->keepLastDeployment;
    }

    public function githubWebhookSecret()
    {
        return $this->githubWebhookSecret;
    }

    public function githubWebhookExecuteUserId()
    {
        return $this->githubWebhookExecuteUserId;
    }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public function updatedAt()
    {
        return $this->updatedAt;
    }

    public function equals($object)
    {
        $equalObjects = false;

        if (!is_null($object) && $object instanceof self) {
            $equalObjects = $this->projectId()->equals($object->projectId());
        }

        return $equalObjects;
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private function addRecipeId(RecipeId $recipeId)
    {
        $this->recipeIds[] = $recipeId;

        return $this;
    }

    private function setServerId(ServerId $serverId)
    {
        $this->serverId = $serverId;

        return $this;
    }

    private function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    private function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    private function setAttribute(ProjectAttribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    private function setEmailNotificationRecipient($emailNotificationRecipient)
    {
        $this->emailNotificationRecipient = $emailNotificationRecipient;

        return $this;
    }

    private function setDaysToKeepDeployments($daysToKeepDeployments)
    {
        $this->daysToKeepDeployments = $daysToKeepDeployments;

        return $this;
    }

    private function setMaxNumberOfDeploymentsToKeep($maxNumberOfDeploymentsToKeep)
    {
        $this->maxNumberOfDeploymentsToKeep = $maxNumberOfDeploymentsToKeep;

        return $this;
    }

    private function setKeetLastDeployment(KeepLastDeployment $keepLastDeployment)
    {
        $this->keepLastDeployment = $keepLastDeployment;

        return $this;
    }

    private function setGithubWebhookSecret($githubWebhookSecret)
    {
        $this->githubWebhookSecret = $githubWebhookSecret;

        return $this;
    }

    private function setGithubWebhookExecuteUserId(UserId $githubWebhookExecuteUserId)
    {
        $this->githubWebhookExecuteUserId = $githubWebhookExecuteUserId;

        return $this;
    }

    private function setCreatedAt(Carbon $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    private function setUpdatedAt(Carbon $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
