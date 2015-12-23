<?php

namespace App\Services\Notification;

use Mail;

class MailNotifier implements NotifierInterface
{
    /**
     * Recipient of notification.
     *
     * @var string
     */
    protected $to;

    /**
     * Sender of notification.
     *
     * @var string
     */
    protected $from;

    /**
     * Recipient of notification.
     *
     * @param string $to The recipient
     * @return App\Services\Notification\NotifierInterface Return self for chainability
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
     * @return App\Services\Notification\NotifierInterface Return self for chainability
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
