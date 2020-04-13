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
        $classAnnotations = $this->getMiddlewares($classAnnotations);
        if (!empty($classAnnotations)) {
            foreach ($classAnnotations as $classAnnotation) {
                $controller->middleware($classAnnotation['middleware'], $classAnnotation['options']);
            }
        }
        return $next($controller);
    }

    public function getMiddlewares($annotations)
    {
        return array_filter(
            array_map(function ($classAnnotation) {
                return $classAnnotation->getResponse()
                    ? ['middleware' => $classAnnotation->getResponse(), 'options' => $classAnnotation->getOptions()]
                    : null;
            }, $annotations)
        );
    }
}
