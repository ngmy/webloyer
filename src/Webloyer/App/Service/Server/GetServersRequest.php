<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Server;

class GetServersRequest
{
    /** @var int|null */
    private $page;
    /** @var int|null */
    private $perPage;

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    /**
     * @param int|null $page
     * @return self
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param int|null $perPage
     * @return self
     */
    public function setPerPage(?int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }
}
