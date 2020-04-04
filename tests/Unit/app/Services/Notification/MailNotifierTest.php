<?php

namespace Tests\Unit\app\Services\Notification;

use App\Services\Notification\MailNotifier;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class MailNotifierTest extends TestCase
{
    use MockeryHelper;

    public function test_Should_SetFromAddressAndReturnThis()
    {
        $notifier = new MailNotifier;

        $result = $notifier->from('from@example.com');

        $this->assertEquals($notifier, $result);
    }

    public function test_Should_SetToAddressAndReturnThis()
    {
        $notifier = new MailNotifier;

        $result = $notifier->to('to@example.com');

        $this->assertEquals($notifier, $result);
    }

    public function test_Should_SendEmailNotification_When_ToAddressIsSet()
    {
        $mockSwiftMailer = $this->mock('Swift_Mailer');
        $mockSwiftMailTransport = $this->mockPartial('Swift_MailTransport');

        $mockSwiftMailer->shouldReceive('send');
        $mockSwiftMailer->shouldReceive('getTransport')
            ->andReturn($mockSwiftMailTransport);

        $this->app['mailer']->setSwiftMailer($mockSwiftMailer);

        $notifier = new MailNotifier;

        $notifier->to('to@example.com')->notify('Subject', 'Message');
    }
}
