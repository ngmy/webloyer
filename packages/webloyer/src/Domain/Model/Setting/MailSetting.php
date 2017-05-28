<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;

class MailSetting extends AbstractEntity
{
    /**
     * @var \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver
     *
     * @Type("string")
     * @Accessor(getter="driverValue",setter="setDriverValue")
     */
    private $driver;

    /**
     * @Type("array")
     * @Accessor(getter="from",setter="setFrom")
     */
    private $from;

    /**
     * @Type("string")
     * @Accessor(getter="smtpHost",setter="setSmtpHost")
     * @SerializedName("smtp_host")
     */
    private $smtpHost;

    /**
     * @Type("integer")
     * @Accessor(getter="smtpPort",setter="setSmtpPort")
     * @SerializedName("smtp_port")
     */
    private $smtpPort;

    /**
     * @var \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption
     *
     * @Type("string")
     * @Accessor(getter="smtpEncryptionValue",setter="setSmtpEncryptionValue")
     * @SerializedName("smtp_encryption")
     */
    private $smtpEncryption;

    /**
     * @Type("string")
     * @Accessor(getter="smtpUserName",setter="setSmtpUserName")
     * @SerializedName("smtp_username")
     */
    private $smtpUserName;

    /**
     * @Type("string")
     * @Accessor(getter="smtpPassword",setter="setSmtpPassword")
     * @SerializedName("smtp_password")
     */
    private $smtpPassword;

    /**
     * @Type("string")
     * @Accessor(getter="sendmailPath",setter="setSendmailPath")
     * @SerializedName("sendmail_path")
     */
    private $sendmailPath;

    public function __construct(MailSettingDriver $driver, array $from, $smtpHost, $smtpPort, $smtpUserName, $smtpPassword, $sendmailPath, MailSettingSmtpEncryption $smtpEncryption = null)
    {
        $this->setDriver($driver);
        $this->setFrom($from);
        $this->setSmtpHost($smtpHost);
        $this->setSmtpPort($smtpPort);
        $this->setSmtpUserName($smtpUserName);
        $this->setSmtpPassword($smtpPassword);
        $this->setSendmailPath($sendmailPath);
        $this->setSmtpEncryption($smtpEncryption);
    }

    public function driver()
    {
        return $this->driver;
    }

    public function driverValue()
    {
        return $this->driver->value();
    }

    public function from()
    {
        return $this->from;
    }

    public function smtpHost()
    {
        return $this->smtpHost;
    }

    public function smtpPort()
    {
        return $this->smtpPort;
    }

    public function smtpEncryption()
    {
        if (is_null($this->smtpEncryption)) {
            return NullMailSettingSmtpEncryption::getInstance();
        }
        return $this->smtpEncryption;
    }

    public function smtpEncryptionValue()
    {
        if (is_null($this->smtpEncryption)) {
            return null;
        }
        return $this->smtpEncryption->value();
    }

    public function smtpUserName()
    {
        return $this->smtpUserName;
    }

    public function smtpPassword()
    {
        return $this->smtpPassword;
    }

    public function sendmailPath()
    {
        return $this->sendmailPath;
    }

    public function setDriver(MailSettingDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function setDriverValue($driverValue)
    {
        $this->setDriver(new MailSettingDriver($driverValue));

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

    public function setSmtpEncryption(MailSettingSmtpEncryption $smtpEncryption = null)
    {
        $this->smtpEncryption = $smtpEncryption;

        return $this;
    }

    public function setSmtpEncryptionValue($smtpEncryptionValue)
    {
        $this->setSmtpEncryption(new MailSettingSmtpEncryption($smtpEncryptionValue));

        return $this;
    }

    public function setSmtpUserName($smtpUserName)
    {
        $this->smtpUserName = $smtpUserName;

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

    public function equals($object)
    {
        return $object == $this;
    }
}
