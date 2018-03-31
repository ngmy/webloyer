<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model;

use RuntimeException;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\ConcurrencySafeTrait;
use TestCase;

class ConcurrencySafeTraitTest extends TestCase
{
    public function test_Should_GetConcurrencyVersion()
    {
        $expectedResult = 'some concurrency version';

        $anonymousUsingConcurrencySafeTrait = $this->createAnonymousUsingConcurrencySafeTrait([
            'concurrencyVersion' => $expectedResult,
        ]);

        $actualResult = $anonymousUsingConcurrencySafeTrait->concurrencyVersion();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_Should_FailWhenConcurrencyViolationThrowException_When_ConcurrencyViolation()
    {
        $concurrencyVersion1 = 'concurrency version 1';
        $concurrencyVersion2 = 'concurrency version 2';

        $anonymousUsingConcurrencySafeTrait = $this->createAnonymousUsingConcurrencySafeTrait([
            'concurrencyVersion' => $concurrencyVersion1,
        ]);

        $anonymousUsingConcurrencySafeTrait->failWhenConcurrencyViolation($concurrencyVersion2);
    }

    public function test_Should_FailWhenConcurrencyViolationNotThrowException_When_ConcurrencyViolation()
    {
        $concurrencyVersion = 'some concurrency version';

        $anonymousUsingConcurrencySafeTrait = $this->createAnonymousUsingConcurrencySafeTrait([
            'concurrencyVersion' => $concurrencyVersion,
        ]);

        try {
            $actualResult = $anonymousUsingConcurrencySafeTrait->failWhenConcurrencyViolation($concurrencyVersion);
        } catch (RuntimeException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertNull($actualResult);
    }

    public function createAnonymousUsingConcurrencySafeTrait(array $params)
    {
        $concurrencyVersion = '';

        extract($params);

        return new class($concurrencyVersion) {
            use ConcurrencySafeTrait;

            public function __construct($concurrencyVersion)
            {
                $this->setConcurrencyVersion($concurrencyVersion);
            }
        };
    }
}
