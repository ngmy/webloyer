<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Recipe;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\ConcurrencySafeTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;

class Recipe extends AbstractEntity
{
    use ConcurrencySafeTrait;

    private $recipeId;

    private $name;

    private $description;

    private $body;

    private $afferentProjectIds = [];

    private $createdAt;

    private $updatedAt;

    public function __construct(RecipeId $recipeId, $name, $description, $body, array $afferentProjectIds, Carbon $createdAt = null, Carbon $updatedAt = null)
    {
        $this->setRecipeId($recipeId);
        $this->setName($name);
        $this->setDescription($description);
        $this->setBody($body);
        array_map([$this, 'addAfferentProjectId'], $afferentProjectIds);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setConcurrencyVersion(md5(serialize($this)));
    }

    public function recipeId()
    {
        return $this->recipeId;
    }

    public function name()
    {
        return $this->name;
    }

    public function description()
    {
        return $this->description;
    }

    public function body()
    {
        return $this->body;
    }

    public function afferentProjectIds()
    {
        return $this->afferentProjectIds;
    }

    public function afferentProjectsCount()
    {
        return count($this->afferentProjectIds);
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
            $equalObjects = $this->recipeId()->equals($object->recipeId());
        }

        return $equalObjects;
    }

    private function setRecipeId(RecipeId $recipeId)
    {
        $this->recipeId = $recipeId;

        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    private function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    private function addAfferentProjectId(ProjectId $afferentProjectId)
    {
        $this->afferentProjectIds[] = $afferentProjectId;

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
