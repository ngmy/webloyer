<?php

namespace Ngmy\Webloyer\Common\QueryObject;

class Pagination
{
    private $page;

    private $limit;

    public function __construct($page, $limit)
    {
        $this->page = $page;
        $this->limit = $limit;
    }

    public function page()
    {
        return $this->page;
    }

    public function limit()
    {
        return $this->limit;
    }
}

