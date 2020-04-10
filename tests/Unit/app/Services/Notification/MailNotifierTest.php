<?php

namespace Tests\Unit\app\Services\Notification;

use App\Services\Notification\MailNotifier;
use Mail;
use Tests\TestCase;

class MailNotifierTest extends TestCase
{
    public function testShouldSetFromAddressAndReturnThis()
    {
        $notifier = new MailNotifier();

        $result = $notifier->from('from@example.com');

        $this->assertEquals($notifier, $result);
    }

    public function testShouldSetToAddressAndReturnThis()
    {
        $notifier = new MailNotifier();

        $result = $notifier->to('to@example.com');

        $this->assertEquals($notifier, $result);
    }

    public function testShouldSendEmailNotificationWhenToAddressIsSet()
    {
        $notifier = new MailNotifier();

        Mail::shouldReceive('raw');

        $notifier->to('to@example.com')->notify('Subject', 'Message');
    }
}
