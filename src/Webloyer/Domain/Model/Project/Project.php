<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

use Common\Domain\Model\Identifiable;
use Webloyer\Domain\Model\Recipe;
use Webloyer\Domain\Model\Server;

class Project
{
    use Identifiable;

    /** @var ProjectId */
    private $id;
    /** @var ProjectName */
    private $name;
    /** @var Recipe\RecipeIds */
    private $recipeIds;
    /** @var Server\ServerId */
    private $serverId;
    /** @var RepositoryUrl */
    private $repositoryUrl;
    /** @var StageName */
    private $stageName;

    /**
     * @param string             $id
     * @param string             $name
     * @param array<int, string> $recipeIds
     * @param string             $serverId
     * @param string             $repositoryUrl
     * @param string             $stageName
     * @return self
     */
    public static function of(
        string $id,
        string $name,
        array $recipeIds,
        string $serverId,
        string $repositoryUrl,
        string $stageName
    ) {
        return new self(
            new ProjectId($id),
            new ProjectName($name),
            Recipe\RecipeIds::of(...$recipeIds),
            new Server\ServerId($serverId),
            new RepositoryUrl($repositoryUrl),
            new StageName($stageName)
        );
    }

    /**
     * @param ProjectId        $id
     * @param ProjectName      $name
     * @param Recipe\RecipeIds $recipeIds
     * @param Server\ServerId  $serverId
     * @return void
     */
    public function __construct(
        ProjectId $id,
        ProjectName $name,
        Recipe\RecipeIds $recipeIds,
        Server\ServerId $serverId,
        RepositoryUrl $repositoryUrl,
        StageName $stageName
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->recipeIds = $recipeIds;
        $this->serverId = $serverId;
        $this->repositoryUrl = $repositoryUrl;
        $this->stageName = $stageName;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * @return array<int, string>
     */
    public function recipeIds(): array
    {
        return $this->recipeIds->toArray();
    }

    /**
     * @return string
     */
    public function serverId(): string
    {
        return $this->serverId->value();
    }

    /**
     * @return string
     */
    public function repositoryUrl(): string
    {
        return $this->repositoryUrl->value();
    }

    /**
     * @return string
     */
    public function stageName(): string
    {
        return $this->stageName->value();
    }

    /**
     * @param ProjectName $name
     * @return self
     */
    public function changeName(ProjectName $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Recipe\RecipeIds $recipeIds
     * @return self
     */
    public function changeRecipes(Recipe\RecipeIds $recipeIds): self
    {
        $this->recipeIds = $recipeIds;
        return $this;
    }

    /**
     * @param Server\ServerId $serverId
     * @return self
     */
    public function changeServer(Server\ServerId $serverId): self
    {
        $this->serverId = $serverId;
        return $this;
    }

    /**
     * @param RepositoryUrl $repositoryUrl
     * @return self
     */
    public function changeRepositoryUrl(RepositoryUrl $repositoryUrl): self
    {
        $this->repositoryUrl = $repositoryUrl;
        return $this;
    }

    /**
     * @param StageName $stageName
     * @return self
     */
    public function changeStageName(StageName $stageName): self
    {
        $this->stageName = $stageName;
        return $this;
    }


    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informId($this->id());
        $interest->informName($this->name());
        $interest->informRecipeIds(...$this->recipeIds());
        $interest->informServerId($this->serverId());
        $interest->informRepositoryUrl($this->repositoryUrl());
        $interest->informStageName($this->stageName());
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->id == $this->id;
        }

        return $equalObjects;
    }
}
