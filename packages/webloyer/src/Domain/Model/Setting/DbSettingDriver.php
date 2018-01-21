<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class DbSettingDriver extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'mysql'     => 'mysql',
        'postgres'  => 'pgsql',
        'sqlite'    => 'sqlite',
        'sqlServer' => 'sqlsrv',
    ];

    public function displayName()
    {
        if ($this->isPostgres()) {
            return 'Postgres';
        }
        if ($this->isSqlite()) {
            return 'SQLite';
        }
        if ($this->isSqlServer()) {
            return 'SQL Server';
        }
        return 'MySQL';
    }

    public function isMysql()
    {
        return $this->equals(self::mysql());
    }

    public function isPostgres()
    {
        return $this->equals(self::postgres());
    }

    public function isSqlite()
    {
        return $this->equals(self::sqlite());
    }

    public function isSqlServer()
    {
        return $this->equals(self::sqlServer());
    }

    public function equals($object)
    {
        return $object == $this;
    }
}
