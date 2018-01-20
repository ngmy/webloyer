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

    /**
     * Create a new entity instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver              $driver
     * @param array                                                                       $from
     * @param string                                                                      $smtpHost
     * @param int                                                                         $smtpPort
     * @param string                                                                      $smtpUserName
     * @param string                                                                      $smtpPassword
     * @param string                                                                      $sendmailPath
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption|null $smtpEncryption
     * @return void
     */
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

    /**
     * Get a driver.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver
     */
    public function driver()
    {
        return $this->driver;
    }

    /**
     * Get a driver value.
     *
     * @return string
     */
    public function driverValue()
    {
        return $this->driver->value();
    }

    /**
     * Get a from address and name.
     *
     * @return array
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * Get a SMTP host.
     *
     * @return string
     */
    public function smtpHost()
    {
        return $this->smtpHost;
    }

    /**
     * Get a SMTP port.
     *
     * @return int
     */
    public function smtpPort()
    {
        return $this->smtpPort;
    }

    /**
     * Get a SMTP encryption.
     *
     * @return Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption|Ngmy\Webloyer\Webloyer\Domain\Model\Setting\NullMailSettingSmtpEncryption
     */
    public function smtpEncryption()
    {
        if (is_null($this->smtpEncryption)) {
            return NullMailSettingSmtpEncryption::getInstance();
        }
        return $this->smtpEncryption;
    }

    /**
     * Get a SMTP encryption value.
     *
     * @return string|null
     */
    public function smtpEncryptionValue()
    {
        if (is_null($this->smtpEncryption)) {
            return null;
        }
        return $this->smtpEncryption->value();
    }

    /**
     * Get a SMTP user name.
     *
     * @return string
     */
    public function smtpUserName()
    {
        return $this->smtpUserName;
    }

    /**
     * Get a SMTP user password.
     *
     * @return string
     */
    public function smtpPassword()
    {
        return $this->smtpPassword;
    }

    /**
     * Get a Sendmail path.
     *
     * @return string
     */
    public function sendmailPath()
    {
        return $this->sendmailPath;
    }

    /**
     * Set a driver.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver $driver
     * @return $this
     */
    public function setDriver(MailSettingDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Set a driver value.
     *
     * @param string $driverValue
     * @return $this
     */
    public function setDriverValue($driverValue)
    {
        $this->setDriver(new MailSettingDriver($driverValue));

        return $this;
    }

    /**
     * Set a from address and name.
     *
     * @param array $from
     * @return $this
     */
    public function setFrom(array $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set a SMTP host.
     *
     * @param string$smtpHost
     * @return $this
     */
    public function setSmtpHost($smtpHost)
    {
        $this->smtpHost = $smtpHost;

        return $this;
    }

    /**
     * Set a SMTP port.
     *
     * @param int $smtpPort
     * @return $this
     */
    public function setSmtpPort($smtpPort)
    {
        $this->smtpPort = $smtpPort;

        return $this;
    }

    /**
     * Set a SMTP encryption.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption|null $smtpEncryption
     * @access public
     * @return $this
     */
    public function setSmtpEncryption(MailSettingSmtpEncryption $smtpEncryption = null)
    {
        $this->smtpEncryption = $smtpEncryption;

        return $this;
    }

    /**
     * Set a SMTP encryption value.
     *
     * @param string $smtpEncryptionValue
     * @return $this
     */
    public function setSmtpEncryptionValue($smtpEncryptionValue)
    {
        $this->setSmtpEncryption(new MailSettingSmtpEncryption($smtpEncryptionValue));

        return $this;
    }

    /**
     * Set SMTP user name.
     *
     * @param string $smtpUserName
     * @return $this
     */
    public function setSmtpUserName($smtpUserName)
    {
        $this->smtpUserName = $smtpUserName;

        return $this;
    }

    /**
     * Set a SMTP password.
     *
     * @param string $smtpPassword
     * @return $this
     */
    public function setSmtpPassword($smtpPassword)
    {
        $this->smtpPassword = $smtpPassword;

        return $this;
    }

    /**
     * Set a Sendmail path.
     *
     * @param string $sendmailPath
     * @return $this
     */
    public function setSendmailPath($sendmailPath)
    {
        $this->sendmailPath = $sendmailPath;

        return $this;
    }

    /**
     * Indicates whether some other object is equal to this one.
     *
     * @param object $object
     * @return bool
     */
    public function equals($object)
    {
        return $object == $this;
    }
}
