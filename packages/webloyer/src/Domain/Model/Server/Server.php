<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Server;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\ConcurrencySafeTrait;
use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;

class Server extends AbstractEntity
{
    use ConcurrencySafeTrait;

    private $serverId;

    private $name;

    private $description;

    private $body;

    private $createdAt;

    private $updatedAt;

    public function __construct(ServerId $serverId, $name, $description, $body, Carbon $createdAt = null, Carbon $updatedAt = null)
    {
        $this->setServerId($serverId);
        $this->setName($name);
        $this->setDescription($description);
        $this->setBody($body);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setConcurrencyVersion(md5(serialize($this)));
    }

    public function serverId()
    {
        return $this->serverId;
    }

    public function name()
    {
        return $this->name;
    }

    public function description()
    {
        return $this->description;
    }

    public function body()
    {
        return $this->body;
    }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public function updatedAt()
    {
        return $this->updatedAt;
    }

    public function equals($object)
    {
        $equalObjects = false;

        if (!is_null($object) && $object instanceof self) {
            $equalObjects = $this->serverId()->equals($object->serverId());
        }

        return $equalObjects;
    }

    private function setServerId(ServerId $serverId)
    {
        $this->serverId = $serverId;

        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    private function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    private function setCreatedAt(Carbon $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    private function setUpdatedAt(Carbon $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
