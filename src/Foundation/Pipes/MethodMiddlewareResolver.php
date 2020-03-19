<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 1:22 AM
 */

namespace M3assy\LaravelAnnotations\Foundation\Pipes;


use Closure;
use Illuminate\Routing\Controller;
use M3assy\LaravelAnnotations\Facades\Annotation;
use M3assy\LaravelAnnotations\Foundation\ControllerReflector;

class MethodMiddlewareResolver
{

    public $reflector;

    public function __construct()
    {
        $this->reflector = (new ControllerReflector())->getMethod();
    }

    public function handle(Controller $controller, Closure $next)
    {
        $methodAnnotations = Annotation::getMethodAnnotations($this->reflector);
        if (!empty($methodAnnotations)) {
            $controller
                ->middleware(
                    array_filter(
                        array_map(function ($methodAnnotation) {
                            return $methodAnnotation->getResponse();
                        }, $methodAnnotations)
                    )
                )->only($this->reflector->getName());
        }
        return $next($controller);
    }
}
