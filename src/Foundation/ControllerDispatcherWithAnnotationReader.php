<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 4/13/2020
 * Time: 7:13 PM
 */

namespace M3assy\LaravelAnnotations\Foundation;


use Illuminate\Routing\ControllerDispatcher;
use M3assy\LaravelAnnotations\Facades\Annotation;

class ControllerDispatcherWithAnnotationReader extends ControllerDispatcher
{
    public function getMiddleware($controller, $method)
    {
        if (! method_exists($controller, 'getMiddleware')) {
            return [];
        }

        Annotation::read($controller);

        return collect($controller->getMiddleware())->reject(function ($data) use ($method) {
            return static::methodExcludedByOptions($method, $data['options']);
        })->pluck('middleware')->all();
    }
}
