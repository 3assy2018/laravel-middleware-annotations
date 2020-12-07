<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 7:34 AM
 */

namespace M3assy\LaravelAnnotations\Foundation\Pipes;


use Closure;
use M3assy\LaravelAnnotations\Facades\Annotation;
use M3assy\LaravelAnnotations\Foundation\AnnotationScanner;
use M3assy\LaravelAnnotations\Console\ControllerResolver;
use M3assy\LaravelAnnotations\Foundation\Pipes\AbstractAclScanner;

class AclScanMethod extends AbstractAclScanner
{
    public function handle(AnnotationScanner $annotationScanner, Closure $next, ...$types)
    {
        $annotations = [];
        $methods = array_unique(array_map(function ($scan) {
            return $scan->getMethod();
        }, $annotationScanner->scans->toArray()));
        foreach ($methods as $method) {
            app(ControllerResolver::class)->setController($method->class);

            $annotations = array_merge_recursive($annotations, [$method->class => Annotation::getMethodAnnotations($method)]);
        }
        foreach ($annotations as $binding => $annotationGroup) {
            app(ControllerResolver::class)->setController($binding);

            $annotationScanner->results =
                array_merge_recursive($annotationScanner->results
                    , ...array_map(function ($annotation) {
                        return [$annotation->getName() => $annotation->getDifference()];
                    }
                        , $this->filterNonSupportedAnnotations($annotationGroup, $types)));
        }
        return $next($annotationScanner);
    }
}
