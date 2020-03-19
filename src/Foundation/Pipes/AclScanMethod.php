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

class AclScanMethod
{
    public function handle(AnnotationScanner $annotationScanner, Closure $next, ...$types)
    {
        $annotations = [];
        $methods = array_unique(array_map(function ($scan) {
            return $scan->getMethod();
        }, $annotationScanner->scans->toArray()));
        foreach ($methods as $method) {
            $annotations = array_merge($annotations, Annotation::getMethodAnnotations($method));
        }
        $annotationScanner->results = array_merge_recursive($annotationScanner->results
            , ...array_map(function ($annotation) {
                return [$annotation->getName() => $annotation->getDifference()];
            }, array_filter($annotations, function ($annotation) use ($types) {
                return array_reduce($types, function ($carry, $current) use ($annotation) {
                    return $carry || ($annotation instanceof $current);
                }, false);
            })));
        return $next($annotationScanner);
    }
}
