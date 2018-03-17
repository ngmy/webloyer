<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Server;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use TestCase;

class ServerTest extends TestCase
{
    public function test_Should_GetServerId()
    {
        $expectedResult = new ServerId(1);

        $server = $this->createServer([
            'serverId' => $expectedResult->id(),
        ]);

        $actualResult = $server->serverId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetName()
    {
        $expectedResult = 'some name';

        $server = $this->createServer([
            'name' => $expectedResult,
        ]);

        $actualResult = $server->name();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDescription()
    {
        $expectedResult = 'some description';

        $server = $this->createServer([
            'description' => $expectedResult,
        ]);

        $actualResult = $server->description();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetBody()
    {
        $expectedResult = 'some body';

        $server = $this->createServer([
            'body' => $expectedResult,
        ]);

        $actualResult = $server->body();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createServer(),
            $this->createServer(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createServer(),
            $this->createServer([
                'serverId' => 2,
            ]),
            false
        );
    }

    public function test_Should_GetCreatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $server = $this->createServer([
            'createdAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $server->createdAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUpdatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $server = $this->createServer([
            'updatedAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $server->updatedAt();

        $this->assertEquals($expectedResult, $actualResult);
    }


    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createServer(array $params = [])
    {
        $serverId = 1;
        $name = '';
        $description = '';
        $body = '';
        $createdAt = '';
        $updatedAt = '';

        extract($params);

        return new Server(
            new ServerId($serverId),
            $name,
            $description,
            $body,
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }
}
