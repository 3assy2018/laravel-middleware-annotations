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
use M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Console\ControllerResolver;
use M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Foundation\Pipes\AbstractAclScanner;

class AclScanClass extends AbstractAclScanner
{
    public function handle(AnnotationScanner $annotationScanner, Closure $next, ...$types)
    {
        $annotations = [];
        $classes = array_unique(array_map(function ($scan) {
            return $scan->getClass();
        }, $annotationScanner->scans->toArray()));
        foreach ($classes as $class) {
            app(ControllerResolver::class)->setController($class->name);

            $annotations = array_merge_recursive($annotations, [$class->name => Annotation::getClassAnnotations($class)]);
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
