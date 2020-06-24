<?php

declare(strict_types=1);

namespace Common\Domain\Model\Event;

/**
 * Tag all domain events that must be sent now to other BCs without the event store.
 *
 * @codeCoverageIgnore
 */
interface PublishNowDomainEvent
{
}
