<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use IteratorAggregate;

class Deployments implements IteratorAggregate
{
    private $deployments = [];

    public function __construct(array $deployments)
    {
        array_walk($deployments, [$this, 'addDeployment']);
    }

    public function getIterator()
    {
        foreach ($this->deployments as $key => $val) {
            yield $key => $val;
        }
    }

    private function addDeployment(Deployment $deployment)
    {
        $this->deployments[] = $deployment;
    }
}
