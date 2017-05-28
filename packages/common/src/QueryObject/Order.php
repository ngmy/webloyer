<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use Ngmy\Webloyer\Common\QueryObject\Direction;

class Order
{
    private $column;

    private $direction;

    public function __construct($column, Direction $direction)
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function column()
    {
        return $this->column;
    }

    public function direction()
    {
        return $this->direction;
    }
}
