<?php

namespace App\Entities\Setting;

use App\Entities\Setting\AbstractSettingEntity;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;

class MailSettingEntity extends AbstractSettingEntity
{
    /**
     * @Type("string")
     * @Accessor(getter="getDriver",setter="setDriver")
     */
    protected $driver;

    /**
     * @Type("array")
     * @Accessor(getter="getFrom",setter="setFrom")
     */
    protected $from;

    /**
     * @Type("string")
     * @Accessor(getter="getSmtpHost",setter="setSmtpHost")
     * @SerializedName("smtp_host")
     */
    protected $smtpHost;

    /**
     * @Type("integer")
     * @Accessor(getter="getSmtpPort",setter="setSmtpPort")
     * @SerializedName("smtp_port")
     */
    protected $smtpPort;

    /**
     * @Type("string")
     * @Accessor(getter="getSmtpEncryption",setter="setSmtpEncryption")
     * @SerializedName("smtp_encryption")
     */
    protected $smtpEncryption;

    /**
     * @Type("string")
     * @Accessor(getter="getSmtpUsername",setter="setSmtpUsername")
     * @SerializedName("smtp_username")
     */
    protected $smtpUsername;

    /**
     * @Type("string")
     * @Accessor(getter="getSmtpPassword",setter="setSmtpPassword")
     * @SerializedName("smtp_password")
     */
    protected $smtpPassword;

    /**
     * @Type("string")
     * @Accessor(getter="getSendmailPath",setter="setSendmailPath")
     * @SerializedName("sendmail_path")
     */
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
