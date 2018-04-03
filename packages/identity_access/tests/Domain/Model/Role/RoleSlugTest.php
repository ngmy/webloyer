<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use TestCase;

class RoleSlugTest extends TestCase
{
    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRoleSlug(),
            $this->createRoleSlug(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRoleSlug(),
            $this->createRoleSlug([
                'value' => 'developer',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createRoleSlug(array $params = [])
    {
        $value = 'administrator';

        extract($params);

        return new RoleSlug($value);
    }
}
