<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 1:21 AM
 */

namespace M3assy\LaravelAnnotations\Foundation\Pipes;


use Closure;
use Illuminate\Routing\Controller;
use M3assy\LaravelAnnotations\Facades\Annotation;
use M3assy\LaravelAnnotations\Foundation\ControllerReflector;

class ClassMiddlewareResolver
{

    public $reflector;

    public function __construct()
    {
        $this->reflector = (new ControllerReflector())->getClass();
    }

    public function handle(Controller $controller, Closure $next)
    {
        $classAnnotations = Annotation::getClassAnnotations($this->reflector);
        if (!empty($classAnnotations)) {
            $controller
                ->middleware(
                    array_filter(
                        array_map(function ($classAnnotation) {
                            return $classAnnotation->getResponse();
                        }, $classAnnotations)
                    )
                );
        }
        return $next($controller);
    }
}
