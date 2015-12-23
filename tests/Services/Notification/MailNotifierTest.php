<?php

use App\Services\Notification\MailNotifier;

class MailNotifierTest extends TestCase
{
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
        $notifier = new MailNotifier;

        $notifier->to('to@example.com')->notify('Subject', 'Message');
    }
}
