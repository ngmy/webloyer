<?php

declare(strict_types=1);

namespace Common\App\Service;

class TransactionalApplicationService implements ApplicationService
{
    /** @var ApplicationService */
    private $service;
    /** @var TransactionalSession */
    private $session;

    /**
     * @param ApplicationService   $service
     * @param TransactionalSession $session
     * @return void
     */
    public function __construct(
        ApplicationService $service,
        TransactionalSession $session
    ) {
        $this->session = $session;
        $this->service = $service;
    }

    /**
     * @param mixed|null $request
     * @return mixed
     * @see ApplicationService::execute()
     */
    public function execute($request = null)
    {
        $operation = function () use ($request) {
            return $this->service->execute($request);
        };
        return $this->session->executeAtomically($operation);
    }
}
