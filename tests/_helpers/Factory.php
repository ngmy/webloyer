<?php

namespace Tests\Helpers;

class Factory
{
    public static function build($class, $data = [])
    {
        $instance = new static;

        return $instance->getInstance($class, $data);
    }

    public static function create($class, $data = [])
    {
        $instance = static::build($class, $data);

        $instance->save();

        return $instance->find($instance->id);
    }

    public static function buildList($class, $dataList = [])
    {
        foreach ($dataList as $data) {
            $instanceList[] = static::build($class, $data);
        }

        return $instanceList;
    }

    public static function createList($class, $dataList = [])
    {
        foreach ($dataList as $data) {
            $instanceList[] = static::create($class, $data);
        }

        return $instanceList;
    }

    protected function getInstance($class, $data = [])
    {
        $instance = new $class;

        foreach ($data as $key => $val) {
            $instance->$key = $val;
        }

        return $instance;
    }
}
