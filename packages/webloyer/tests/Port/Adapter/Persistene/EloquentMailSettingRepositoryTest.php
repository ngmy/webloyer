<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingSmtpEncryption;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentMailSettingRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Setting as EloquentSetting;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentMailSettingRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetMailSettingOfId()
    {
        $createdEloquentSetting = EloquentFactory::create(EloquentSetting::class, [
            'type' => 'mail',
            'attributes' => $this->createMailSetting(),
        ]);
        $expectedResult = $createdEloquentSetting->attributes;

        $actualResult = $this->createEloquentMailSettingRepository()->mailSetting();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewMailSetting()
    {
        $newMailSetting = $this->createMailSetting();

        $returnedMailSetting = $this->createEloquentMailSettingRepository()->save($newMailSetting);

        $createdEloquentSetting = EloquentSetting::where('type', 'mail')->first();

        $this->assertEquals($newMailSetting, $createdEloquentSetting->attributes);

        $this->assertEquals($newMailSetting, $returnedMailSetting);
    }

    public function test_Should_UpdateExistingMailSetting()
    {
        $eloquentMailSettingShouldBeUpdated = EloquentFactory::create(EloquentSetting::class);

        $newMailSetting = $this->createMailSetting([
            'driver' => 'sendmail',
        ]);

        $returnedMailSetting = $this->createEloquentMailSettingRepository()->save($newMailSetting);

        $updatedEloquentSetting = EloquentSetting::where('type', 'mail')->first();

        $this->assertEquals($newMailSetting, $updatedEloquentSetting->attributes);

        $this->assertEquals($newMailSetting, $returnedMailSetting);
    }

    private function createMailSetting(array $params = [])
    {
        $driver = 'smtp';
        $from = [
            'address' => '',
            'name' => '',
        ];
        $smtpHost = '';
        $smtpPort = 25;
        $smtpUserName = '';
        $smtpPassword = '';
        $sendmailPath = '';
        $smtpEncryption = 'tls';

        extract($params);

        if (!is_null($smtpEncryption)) {
            $smtpEncryption = new MailSettingSmtpEncryption($smtpEncryption);
        }

        return new MailSetting(
            new MailSettingDriver($driver),
            $from,
            $smtpHost,
            $smtpPort,
            $smtpUserName,
            $smtpPassword,
            $sendmailPath,
            $smtpEncryption
        );
    }

    private function createEloquentMailSettingRepository(array $params = [])
    {
        extract($params);

        return new EloquentMailSettingRepository(new EloquentSetting());
    }
}
