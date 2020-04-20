<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

/**
 * @codeCoverageIgnore
 */
interface DeploymentInterest
{
    /**
     * @param string $projectId
     * @return void
     */
    public function informProjectId(string $projectId): void;
    /**
     * @param int $number
     * @return void
     */
    public function informNumber(int $number): void;
    /**
     * @param string $task
     * @return void
     */
    public function informTask(string $task): void;
    /**
     * @param string $status
     * @return void
     */
    public function informStatus(string $status): void;
    /**
     * @param string $log
     * @return void
     */
    public function informLog(string $log): void;
    /**
     * @param string $executor
     * @return void
     */
    public function informExecutor(string $executor): void;
}
