<?php

declare(strict_types=1);

namespace Common\App\Service;

interface ApplicationService
{
    /**
     * @param mixed|null $request
     * @return mixed
     */
    public function execute($request = null);
}
