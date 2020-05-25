<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

class Projects
{
    /** @var array<int, Project> */
    private $projects;

    public static function empty(): self
    {
        return new self(...[]);
    }

    /**
     * @param Project ...$projects
     * @return void
     */
    public function __construct(Project ...$projects)
    {
        $this->projects = $projects;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->projects);
    }

    /**
     * @return array<int, Project>
     */
    public function toArray(): array
    {
        return $this->projects;
    }
}
