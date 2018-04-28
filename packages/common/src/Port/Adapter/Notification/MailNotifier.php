<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Notification;

use Mail;
use Ngmy\Webloyer\Common\Notification\NotifierInterface;

class MailNotifier implements NotifierInterface
{
    /**
     * Recipient of notification.
     *
     * @var string
     */
    private $to;

    /**
     * Sender of notification.
     *
     * @var string
     */
    private $from;

    /**
     * Recipient of notification.
     *
     * @param string $to The recipient
     * @return \Ngmy\Webloyer\Common\Notification\NotifierInterface Return self for chainability
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Sender of notification.
     *
     * @param string $from The sender
     * @return \Ngmy\Webloyer\Common\Notification\NotifierInterface Return self for chainability
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Send notification.
     *
     * @param string $subject The subject of notification
     * @param string $message The message of notification
     * @return void
     */
    public function notify($subject, $message)
    {
        Mail::raw($message, function ($m) use ($subject) {
            $m->to($this->to)->subject($subject);
        });
    }
}
