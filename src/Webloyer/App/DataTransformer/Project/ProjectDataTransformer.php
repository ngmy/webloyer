<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\App\DataTransformer\Recipe\RecipesDataTransformer;
use Webloyer\App\DataTransformer\Server\ServerDataTransformer;
use Webloyer\App\DataTransformer\User\UserDataTransformer;
use Webloyer\Domain\Model\Project\Project;

/**
 * @codeCoverageIgnore
 */
interface ProjectDataTransformer
{
    /**
     * @param Project $project
     * @return self
     */
    public function write(Project $project): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @param RecipesDataTransformer $recipesDataTransformer
     * @return self
     */
    public function setRecipesDataTransformer(RecipesDataTransformer $recipesDataTransformer): self;
    /**
     * @param ServerDataTransformer $serverDataTransformer
     * @return self
     */
    public function setServerDataTransformer(ServerDataTransformer $serverDataTransformer): self;
    /**
     * @param UserDataTransformer $userDataTransformer
     * @return self
     */
    public function setUserDataTransformer(UserDataTransformer $userDataTransformer): self;
}
