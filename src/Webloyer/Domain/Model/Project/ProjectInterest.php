<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

/**
 * @codeCoverageIgnore
 */
interface ProjectInterest
{
    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void;
    /**
     * @param string $name
     * @return void
     */
    public function informName(string $name): void;
    /**
     * @param string ...$recipeIds
     * @return void
     */
    public function informRecipeIds(string ...$recipeIds): void;
    /**
     * @param string $serverId
     * @return void
     */
    public function informServerId(string $serverId): void;
    /**
     * @param string $repositoryUrl
     * @return void
     */
    public function informRepositoryUrl(string $repositoryUrl): void;
    /**
     * @param string $stageName
     * @return void
     */
    public function informStageName(string $stageName): void;
}
