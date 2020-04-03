<?php

namespace Tests\Unit\app\Entities\Setting;

use App\Entities\Setting\AppSettingEntity;

class AppSettingEntityTest extends TestCase
{
    public function test_Should_SetAndGetUrl()
    {
        $appSettingEntity = new AppSettingEntity;

        $appSettingEntity->setUrl('http://example.com');

        $url = $appSettingEntity->getUrl();

        $this->assertEquals('http://example.com', $url);
    }
}
