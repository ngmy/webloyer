<?php

namespace Ngmy\Webloyer\Common\QueryObject;

class Limit
{
    private $offset;

    private $limit;

    public function __construct($offset, $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function offset()
    {
        return $this->offset;
    }

    public function limit()
    {
        return $this->limit;
    }
}
