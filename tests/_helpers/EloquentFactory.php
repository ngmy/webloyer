<?php

namespace Tests\Helpers;

class EloquentFactory
{
    public static function build($eloquentClass, array $params = [])
    {
        $eloquentInstance = new $eloquentClass;

        foreach ($params as $key => $val) {
            $eloquentInstance->$key = $val;
        }

        return $eloquentInstance;
    }

    public static function create($eloquentClass, array $params = [])
    {
        $eloquentInstance = static::build($eloquentClass, $params);

        $eloquentInstance->save();

        return $eloquentInstance->find($eloquentInstance->id);
    }

    public static function buildList($eloquentClass, array $paramsList = [])
    {
        foreach ($paramsList as $params) {
            $eloquentInstanceList[] = static::build($eloquentClass, $params);
        }

        return $eloquentInstanceList;
    }

    public static function createList($eloquentClass, array $paramsList = [])
    {
        foreach ($paramsList as $params) {
            $eloquentInstanceList[] = static::create($eloquentClass, $params);
        }

        return $eloquentInstanceList;
    }
}
