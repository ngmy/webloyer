<?php
declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Entities\Setting\MailSettingEntity;
use App\Repositories\AbstractConfigRepository;

/**
 * Class ConfigMailSetting
 * @package App\Repositories\Setting
 */
class ConfigMailSetting extends AbstractConfigRepository implements MailSettingInterface
{
    /**
     * @return MailSettingEntity|mixed|void
     */
    public function all()
    {
        $driver          = $this->reader->getConfig('MAIL_DRIVER');
        $fromAddress     = $this->reader->getConfig('MAIL_FROM_ADDRESS');
        $fromName        = $this->reader->getConfig('MAIL_FROM_NAME');
        $smtpHost        = $this->reader->getConfig('MAIL_HOST');
        $smtpPort        = $this->reader->getConfig('MAIL_PORT');
        $smtpEncryption  = $this->reader->getConfig('MAIL_ENCRYPTION');
        $smtpUsername    = $this->reader->getConfig('MAIL_USERNAME');
        $smtpPassword    = $this->reader->getConfig('MAIL_PASSWORD');
        $sendmailPath    = $this->reader->getConfig('MAIL_SENDMAIL');

        $from = [
            'address' => $fromAddress,
            'name'    => $fromName,
        ];

        $mailSetting = new MailSettingEntity;
        $mailSetting->setDriver($driver)
            ->setFrom($from)
            ->setSmtpHost($smtpHost)
            ->setSmtpPort($smtpPort)
            ->setSmtpEncryption($smtpEncryption)
            ->setSmtpUsername($smtpUsername)
            ->setSmtpPassword($smtpPassword)
            ->setSendmailPath($sendmailPath);

        return $mailSetting;
    }

    /**
     * @param array $data
     * @return bool|mixed|void
     */
    public function update(array $data)
    {
        $this->writer->setConfig('MAIL_DRIVER',       $data['driver']);
        $this->writer->setConfig('MAIL_FROM_ADDRESS', $data['from_address']);
        $this->writer->setConfig('MAIL_FROM_NAME',    $data['from_name']);
        $this->writer->setConfig('MAIL_HOST',         $data['smtp_host']);
        $this->writer->setConfig('MAIL_PORT',         $data['smtp_port']);
        $this->writer->setConfig('MAIL_ENCRYPTION',   $data['smtp_encryption']);
        $this->writer->setConfig('MAIL_USERNAME',     $data['smtp_username']);
        $this->writer->setConfig('MAIL_PASSWORD',     $data['smtp_password']);
        $this->writer->setConfig('MAIL_SENDMAIL',     $data['sendmail_path']);

        return true;
    }
}
