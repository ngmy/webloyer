<?php
declare(strict_types=1);

namespace App\Entities\Setting;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class MailSettingEntity
 * @package App\Entities\Setting
 */
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

    /**
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getSmtpHost()
    {
        return $this->smtpHost;
    }

    /**
     * @return mixed
     */
    public function getSmtpPort()
    {
        return $this->smtpPort;
    }

    /**
     * @return mixed
     */
    public function getSmtpEncryption()
    {
        return $this->smtpEncryption;
    }

    /**
     * @return mixed
     */
    public function getSmtpUsername()
    {
        return $this->smtpUsername;
    }

    /**
     * @return mixed
     */
    public function getSmtpPassword()
    {
        return $this->smtpPassword;
    }

    /**
     * @return mixed
     */
    public function getSendmailPath()
    {
        return $this->sendmailPath;
    }

    /**
     * @param $driver
     * @return $this
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @param array $from
     * @return $this
     */
    public function setFrom(array $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param $smtpHost
     * @return $this
     */
    public function setSmtpHost($smtpHost)
    {
        $this->smtpHost = $smtpHost;
        return $this;
    }

    /**
     * @param $smtpPort
     * @return $this
     */
    public function setSmtpPort($smtpPort)
    {
        $this->smtpPort = $smtpPort;
        return $this;
    }

    /**
     * @param $smtpEncryption
     * @return $this
     */
    public function setSmtpEncryption($smtpEncryption)
    {
        $this->smtpEncryption = $smtpEncryption;
        return $this;
    }

    /**
     * @param $smtpUsername
     * @return $this
     */
    public function setSmtpUsername($smtpUsername)
    {
        $this->smtpUsername = $smtpUsername;
        return $this;
    }

    /**
     * @param $smtpPassword
     * @return $this
     */
    public function setSmtpPassword($smtpPassword)
    {
        $this->smtpPassword = $smtpPassword;
        return $this;
    }

    /**
     * @param $sendmailPath
     * @return $this
     */
    public function setSendmailPath($sendmailPath)
    {
        $this->sendmailPath = $sendmailPath;
        return $this;
    }
}
