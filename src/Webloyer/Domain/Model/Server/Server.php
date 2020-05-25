<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

use Common\Domain\Model\Identity\Identifiable;
use Common\Domain\Model\Timestamp\Timestampable;

class Server
{
    use Identifiable;
    use Timestampable;

    /** @var ServerId */
    private $id;
    /** @var ServerName */
    private $name;
    /** @var ServerDescription */
    private $description;
    /** @var ServerBody */
    private $body;

    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $description
     * @param string      $body
     * @return self
     */
    public static function of(
        string $id,
        string $name,
        ?string $description,
        string $body
    ): self {
        return new self(
            new ServerId($id),
            new ServerName($name),
            new ServerDescription($description),
            new ServerBody($body)
        );
    }

    /**
     * @param ServerId          $id
     * @param ServerName        $name
     * @param ServerDescription $description
     * @param ServerBody        $body
     * @return void
     */
    public function __construct(
        ServerId $id,
        ServerName $name,
        ServerDescription $description,
        ServerBody $body
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description->value();
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return $this->body->value();
    }

    /**
     * @param ServerName $name
     * @return self
     */
    public function changeName(ServerName $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param ServerDescription $description
     * @return self
     */
    public function changeDescription(ServerDescription $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param ServerBody $body
     * @return self
     */
    public function changeBody(ServerBody $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param ServerInterest $interest
     * @return void
     */
    public function provide(ServerInterest $interest): void
    {
        $interest->informId($this->id());
        $interest->informName($this->name());
        $interest->informDescription($this->description());
        $interest->informBody($this->body());
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->id == $this->id;
        }

        return $equalObjects;
    }
}
