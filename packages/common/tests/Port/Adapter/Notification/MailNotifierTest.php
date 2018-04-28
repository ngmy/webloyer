<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Notification;

use Ngmy\Webloyer\Common\Port\Adapter\Notification\MailNotifier;
use Swift_Mailer;
use Swift_MailTransport;
use Tests\Helpers\MockeryHelper;
use TestCase;

class MailNotifierTest extends TestCase
{
    use MockeryHelper;

    public function setUp()
    {
        parent::setUp();

        $this->closeMock();
    }

    public function test_Should_SetFromAddressAndReturnThis()
    {
        $expectedResult = $this->createMailNotifier();

        $actualResult = $expectedResult->from('from@example.com');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SetToAddressAndReturnThis()
    {
        $expectedResult = $this->createMailNotifier();

        $actualResult = $expectedResult->to('to@example.com');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SendEmailNotification_When_ToAddressIsSet()
    {
        $mailNotifier = $this->createMailNotifier();

        $mailNotifier->to('to@example.com')->notify('Subject', 'Message');

        $this->assertTrue(true);
    }

    private function createMailNotifier(array $params = [])
    {
        extract($params);

        $swiftMailer = $this->mock(Swift_Mailer::class);
        $swiftMailTransport = $this->partialMock(Swift_MailTransport::class);
        $swiftMailer->shouldReceive('send');
        $swiftMailer->shouldReceive('getTransport')->andReturn($swiftMailTransport);
        $this->app['mailer']->setSwiftMailer($swiftMailer);

        return new MailNotifier();
    }
}
