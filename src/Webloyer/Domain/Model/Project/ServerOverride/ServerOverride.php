<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\ServerOverride;

use Webloyer\Domain\Model\Project\ProjectInterest;

class ServerOverride
{
    /** @var DeployPath|null */
    private $deployPath;

    /**
     * @param string|null $deployPath
     * @return self
     */
    public static function of(?string $deployPath): self
    {
        return new self(
            isset($deployPath) ? new DeployPath($deployPath) : null
        );
    }

    /**
     * @param DeployPath|null $deployPath
     * @return void
     */
    public function __construct(?DeployPath $deployPath)
    {
        $this->deployPath = $deployPath;
    }

    /**
     * @return string|null
     */
    public function deployPath(): ?string
    {
        return isset($this->deployPath) ? $this->deployPath->value() : null;
    }

    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informDeployPath($this->deployPath());
    }
}
