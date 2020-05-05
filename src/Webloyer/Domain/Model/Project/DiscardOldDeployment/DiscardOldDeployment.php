<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project\DiscardOldDeployment;

use Webloyer\Domain\Model\Project\ProjectInterest;

class DiscardOldDeployment
{
    /** @var KeepDays|null */
    private $keepDays;
    /** @var KeepLastDeployment|null */
    private $keepLastDeployment;
    /** @var KeepMaxNumber|null */
    private $keepMaxNumber;

    /**
     * @param int|null $keepDays
     * @param bool     $keepLastDeployment
     * @param int|null $keepMaxNumber
     * @return self
     */
    public static function of(
        ?int $keepDays,
        bool $keepLastDeployment,
        ?int $keepMaxNumber
    ): self {
        return new self(
            isset($keepDays) ? new KeepDays($keepDays) : null,
            new KeepLastDeployment($keepLastDeployment),
            isset($keepMaxNumber) ? new KeepMaxNumber($keepMaxNumber) : null
        );
    }

    /**
     * @param KeepDays|null           $keepDays
     * @param KeepLastDeployment|null $keepLastDeployment
     * @param KeepMaxNumber|null      $keepMaxNumber
     * @return void
     */
    public function __construct(
        ?KeepDays $keepDays,
        ?KeepLastDeployment $keepLastDeployment,
        ?KeepMaxNumber $keepMaxNumber
    ) {
        $this->keepDays = $keepDays;
        $this->keepLastDeployment = $keepLastDeployment;
        $this->keepMaxNumber = $keepMaxNumber;
    }

    /**
     * @return int|null
     */
    public function keepDays(): ?int
    {
        return $this->isKeepDays() ? $this->keepDays->value() : null;
    }

    /**
     * @return bool
     */
    public function keepLastDeployment(): bool
    {
        return isset($this->keepLastDeployment) ? $this->keepLastDeployment->value() : false;
    }

    /**
     * @return int|null
     */
    public function keepMaxNumber(): ?int
    {
        return $this->isKeepMaxNumber() ? $this->keepMaxNumber->value() : null;
    }

    public function isKeepDays(): bool
    {
        return isset($this->keepDays);
    }

    public function isKeepMaxNumber(): bool
    {
        return isset($this->keepDays);
    }

    public function isEnable(): bool
    {
        return $this->isKeepDays() && $this->isKeepMaxNumber();
    }

    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informDeploymentKeepDays($this->keepDays());
        $interest->informKeepLastDeployment($this->keepLastDeployment());
        $interest->informDeploymentKeepMaxNumber($this->keepMaxNumber());
    }
}
