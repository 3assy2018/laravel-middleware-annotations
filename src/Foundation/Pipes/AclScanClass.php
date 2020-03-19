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

class AclScanClass
{
    public function handle(AnnotationScanner $annotationScanner, Closure $next, ...$types)
    {
        $annotations = [];
        $classes = array_unique(array_map(function ($scan) {
            return $scan->getClass();
        }, $annotationScanner->scans->toArray()));
        foreach ($classes as $class) {
            $annotations = array_merge($annotations, Annotation::getClassAnnotations($class));
        }
        $annotationScanner->results = array_unique(array_merge_recursive($annotationScanner->results
            , ...array_map(function ($annotation) {
                return [$annotation->getName() => $annotation->getDifference()];
            }
                , array_filter($annotations, function ($annotation) use ($types) {
                    return array_reduce($types, function ($carry, $current) use ($annotation) {
                        return $carry || ($annotation instanceof $current);
                    }, false);
            }))));
        return $next($annotationScanner);
    }
}
