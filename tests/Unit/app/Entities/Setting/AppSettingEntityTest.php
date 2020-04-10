<?php

namespace Tests\Unit\app\Entities\Setting;

use App\Entities\Setting\AppSettingEntity;
use Tests\TestCase;

class AppSettingEntityTest extends TestCase
{
    public function testShouldSetAndGetUrl()
    {
        $appSettingEntity = new AppSettingEntity();

        $appSettingEntity->setUrl('http://example.com');

        $url = $appSettingEntity->getUrl();

        $this->assertEquals('http://example.com', $url);
    }
}
