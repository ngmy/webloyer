<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Common\Enum\EnumTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractValueObject;

final class Status extends AbstractValueObject
{
    use EnumTrait;

    const ENUM = [
        'success' => 0,
        'failure' => 1,
        'running' => 2,
    ];

    public static function fromProcessExitCode($processExitCode)
    {
        if (is_null($processExitCode)) {
            return self::running();
        } elseif ($processExitCode == 0) {
            return self::success();
        } else {
            return self::failure();
        }
    }

    public function text()
    {
        if ($this->isSuccess()) {
            return 'Success';
        } elseif ($this->isFailure()) {
            return 'Failure';
        } else {
            return 'Running';
        }
    }

    public function isSuccess()
    {
        return $this->equals(self::success());
    }

    public function isFailure()
    {
        return $this->equals(self::failure());
    }

    public function isRunning()
    {
        return $this->equals(self::running());
    }

    public function equals($object)
    {
        return $object == $this;
    }
}
