<?php

namespace Ngmy\Webloyer\Webloyer\Application\Server;

use Mockery;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface;
use TestCase;
use Tests\Helpers\MockeryHelper;

class ServerServiceTest extends TestCase
{
    use MockeryHelper;

    private $serverService;

    private $serverRepository;

    private $inputForGetServersByPage = [
        'page'    => 1,
        'perPage' => 10,
    ];

    private $inputForSaveServer = [
        'serverId'           => 1,
        'name'               => '',
        'description'        => '',
        'body'               => '',
        'concurrencyVersion' => '',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->serverRepository = $this->mock(ServerRepositoryInterface::class);
        $this->serverService = new ServerService(
            $this->serverRepository
        );
    }

    public function test_Should_GetAllServers()
    {
        $expectedResult = true;
        $this->serverRepository
            ->shouldReceive('allServers')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->serverService->getAllServers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetServersByPage_When_PageAndPerPageIsNotSpecified()
    {
        $this->checkGetServersByPage(null, null, 1, 10);
    }

    public function test_Should_GetServersByPage_When_PageAndPerPageIsSpecified()
    {
        $this->checkGetServersByPage(2, 20, 2, 20);
    }

    public function test_Should_GetServerById()
    {
        $serverId = 1;
        $expectedResult = true;
        $this->serverRepository
            ->shouldReceive('serverOfId')
            ->with(Mockery::on(function ($arg) use ($serverId) {
                return $arg == new ServerId($serverId);
            }))
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->serverService->getServerById($serverId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SaveServer_When_ServerIdIsNull()
    {
        $this->checkSaveServer(true, false);
    }

    public function test_Should_SaveServer_When_ServerIdIsNotNullAndServerExists()
    {
        $this->checkSaveServer(true, true);
    }

    public function test_Should_SaveServer_When_ServerIdIsNotNullAndServerNotExists()
    {
        $this->checkSaveServer(true, false);
    }

    public function test_Should_RemoveServer()
    {
        $serverId = 1;
        $server = $this->mock(Server::class);
        $this->serverRepository
            ->shouldReceive('serverOfId')
            ->with(Mockery::on(function ($arg) use ($serverId) {
                return $arg == new ServerId($serverId);
            }))
            ->once()
            ->andReturn($server);
        $this->serverRepository
            ->shouldReceive('remove')
            ->with($server)
            ->once();

        $this->serverService->removeServer($serverId);

        $this->assertTrue(true);
    }

    private function checkGetServersByPage($inputPage, $inputPerPage, $expectedPage, $expectedPerPage)
    {
        $this->inputForGetServersByPage['page'] = $inputPage;
        $this->inputForGetServersByPage['perPage'] = $inputPerPage;

        $expectedResult = true;
        $this->serverRepository
            ->shouldReceive('serversOfPage')
            ->with($expectedPage, $expectedPerPage)
            ->once()
            ->andReturn($expectedResult);

        extract($this->inputForGetServersByPage);

        if (isset($page) && isset($perPage)) {
            $actualResult = $this->serverService->getServersByPage($page, $perPage);
        } elseif (isset($page)) {
            $actualResult = $this->serverService->getServersByPage($page);
        } else {
            $actualResult = $this->serverService->getServersByPage();
        }

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function checkSaveServer($isNullInputServerId, $existsServer)
    {
        if ($isNullInputServerId) {
            $this->inputForSaveServer['serverId'] = null;
        } else {
            $this->inputForSaveServer['serverId'] = 1;
            if ($existsServer) {
                $server = $this->mock(Server::class);
                $server
                    ->shouldReceive('failWhenConcurrencyViolation')
                    ->with($this->inputForSaveServer['concurrencyVersion'])
                    ->once();
            } else {
                $server = null;
            }
            $this->serverRepository
                ->shouldReceive('serverOfId')
                ->with(Mockery::on(function ($arg) {
                    return $arg == new ServerId($this->inputForSaveServer['serverId']);
                }))
                ->once()
                ->andReturn($server);
        }

        $this->serverRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveServer);
                $server = new Server(
                    new ServerId($serverId),
                    $name,
                    $description,
                    $body,
                    null,
                    null
                );
                return $arg == $server;
            }))
            ->once();

        extract($this->inputForSaveServer);

        $this->serverService->saveServer(
            $serverId,
            $name,
            $description,
            $body,
            $concurrencyVersion
        );

        $this->assertTrue(true);
    }
}
