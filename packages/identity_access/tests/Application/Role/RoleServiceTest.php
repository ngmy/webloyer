<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\Role;

use Mockery;
use Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;
use TestCase;
use Tests\Helpers\MockeryHelper;

class RoleServiceTest extends TestCase
{
    use MockeryHelper;

    private $roleRepository;

    private $roleService;

    public function setUp()
    {
        parent::setUp();

        $this->roleRepository = $this->mock(RoleRepositoryInterface::class);
        $this->roleService = new RoleService($this->roleRepository);
    }

    public function test_Should_GetAllRoles()
    {
        $expectedResult = true;

        $this->roleRepository
            ->shouldReceive('allRoles')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->roleService->getAllRoles();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRoleBySlug()
    {
        $expectedResult = true;
        $roleSlug = new RoleSlug('administrator');

        $this->roleRepository
            ->shouldReceive('roleOfSlug')
            ->with(\Hamcrest\Matchers::equalTo($roleSlug))
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->roleService->getRoleBySlug($roleSlug->value());

        $this->assertEquals($expectedResult, $actualResult);
    }
}
