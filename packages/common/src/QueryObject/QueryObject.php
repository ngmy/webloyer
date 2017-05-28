<?php

namespace Ngmy\Webloyer\Common\QueryObject;

use InvalidArgumentException;
use Ngmy\Webloyer\Common\QueryObject\AbstractCriteria;
use Ngmy\Webloyer\Common\QueryObject\Limit;
use Ngmy\Webloyer\Common\QueryObject\Order;
use Ngmy\Webloyer\Common\QueryObject\Pagination;

class QueryObject
{
    private $criteria;

    private $orders = [];

    private $limit;

    private $pagination;

    public function criteria()
    {
        return $this->criteria;
    }

    public function orders()
    {
        return $this->orders;
    }

    public function limit()
    {
        return $this->limit;
    }

    public function pagination()
    {
        return $this->pagination;
    }

    public function setCriteria(AbstractCriteria $criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
        return $this;
    }

    public function setLimit(Limit $limit)
    {
        if (!is_null($this->pagination)) {
            throw new InvalidArgumentException();
        }
        $this->limit = $limit;
        return $this;
    }

    public function setPagination(Pagination $pagination)
    {
        if (!is_null($this->limit)) {
            throw new InvalidArgumentException();
        }
        $this->pagination = $pagination;
        return $this;
    }
}
