<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

class Deployments
{
    /** @var list<Deployment> */
    private $deployments;

    public static function empty(): self
    {
        return new self(...[]);
    }

    /**
     * @param Deployment ...$deployments
     * @return void
     */
    public function __construct(Deployment ...$deployments)
    {
        $this->deployments = $deployments;
    }

    /**
     * @return list<Deployment>
     */
    public function toArray(): array
    {
        return $this->deployments;
    }

    public function latest(): ?Deployment
    {
        if (empty($this->deployments)) {
            return null;
        }
        return $this->deployments[0];
    }

    public function oldest(): ?Deployment
    {
        if (empty($this->deployments)) {
            return null;
        }
        $count = count($this->deployments);
        return $this->deployments[$count - 1];
    }

    public function indexOf(Deployment $needle): int
    {
        foreach ($this->deployments as $i => $deployment) {
            if ($needle->equals($deployment)) {
                return $i;
            }
        }
        return -1;
    }

    public function lastIndexOf(Deployment $needle): int
    {
        foreach (array_reverse($this->deployments) as $i => $deployment) {
            if ($needle->equals($deployment)) {
                return $i;
            }
        }
        return -1;
    }

    public function isEmpty(): bool
    {
        return empty($this->deployments);
    }
}
