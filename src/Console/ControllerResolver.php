<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 4/13/2020
 * Time: 9:04 PM
 */

namespace M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Console;


class ControllerResolver
{
    protected $controller;

    public function __construct()
    {
        $this->controller = request()->route() ? request()->route()->controller : null;
    }

    public function setController($controller)
    {
        $this->controller = app($controller);
    }

    public function getController()
    {
        return $this->controller;
    }
}
