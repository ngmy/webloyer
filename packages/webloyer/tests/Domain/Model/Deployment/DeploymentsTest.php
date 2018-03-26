<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployments;
use TestCase;
use Tests\Helpers\MockeryHelper;

class DeploymentsTest extends TestCase
{
    use MockeryHelper;

    public function test_Should_GetIterator()
    {
        $expectedResult = [
            $this->mock(Deployment::class),
            $this->mock(Deployment::class),
        ];

        $deployments = new Deployments($expectedResult);

        foreach ($deployments as $i => $actualResult) {
            $this->assertEquals($expectedResult[$i], $actualResult);
        }
    }
}
