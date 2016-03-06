<?php

namespace App\Entities\Setting;

use App\Entities\Setting\AbstractSettingEntity;

class MailSettingEntity extends AbstractSettingEntity
{
    protected $driver;

    protected $from;

    protected $smtpHost;

    protected $smtpPort;

    protected $smtpEncryption;

    protected $smtpUsername;

    protected $smtpPassword;

    protected $sendmailPath;

    public function getDriver()
    {
        return $this->driver;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getSmtpHost()
    {
        return $this->smtpHost;
    }

    public function getSmtpPort()
    {
        return $this->smtpPort;
    }

    public function getSmtpEncryption()
    {
        return $this->smtpEncryption;
    }

    public function getSmtpUsername()
    {
        return $this->smtpUsername;
    }

    public function getSmtpPassword()
    {
        return $this->smtpPassword;
    }

    public function getSendmailPath()
    {
        return $this->sendmailPath;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function setFrom(array $from)
    {
        $this->from = $from;

        return $this;
    }

    public function setSmtpHost($smtpHost)
    {
        $this->smtpHost = $smtpHost;

        return $this;
    }

    public function setSmtpPort($smtpPort)
    {
        $this->smtpPort = $smtpPort;

        return $this;
    }

    public function setSmtpEncryption($smtpEncryption)
    {
        $this->smtpEncryption = $smtpEncryption;

        return $this;
    }

    public function setSmtpUsername($smtpUsername)
    {
        $this->smtpUsername = $smtpUsername;

        return $this;
    }

    public function setSmtpPassword($smtpPassword)
    {
        $this->smtpPassword = $smtpPassword;

        return $this;
    }

    public function setSendmailPath($sendmailPath)
    {
        $this->sendmailPath = $sendmailPath;

        return $this;
    }
}
