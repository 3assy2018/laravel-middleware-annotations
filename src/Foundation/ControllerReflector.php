<?php

namespace M3assy\LaravelAnnotations\Foundation;


use ReflectionClass;

class ControllerReflector
{
    protected $routeQuery;

    public function __construct()
    {
        $this->routeQuery = request()->route() ? request()->route()->getAction('controller') : null;
    }

    /**
     * @param null $key
     * @return array
     */
    public function resolveRouteQuery($key = null)
    {
        $routeQueryResult = array_combine(["class", "method"],explode("@", $this->routeQuery));
        return $key ? $routeQueryResult[$key] : $routeQueryResult;
    }

    /**
     * @return ReflectionClass
     * @throws \ReflectionException
     */
    public function getClass()
    {
        return new ReflectionClass($this->resolveRouteQuery("class"));
    }

    /**
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    public function getMethod()
    {
        $routeQuery = $this->resolveRouteQuery();
        
        return (new ReflectionClass($routeQuery["class"]))
                ->getMethod($routeQuery["method"]);
    }

    /**
     * @param $query
     * @return $this
     */
    public function setRouteQuery($query)
    {
        $this->routeQuery = $query;
        return $this;
    }
}
