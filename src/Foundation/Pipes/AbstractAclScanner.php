<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 4/13/2020
 * Time: 8:15 PM
 */

namespace M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Foundation\Pipes;


use Closure;
use M3assy\LaravelAnnotations\Foundation\AnnotationScanner;

abstract class AbstractAclScanner
{

    abstract public function handle(AnnotationScanner $annotationScanner, Closure $next, ...$types);

    protected function isAnnotationSupported($annotation, $types)
    {
        return array_reduce($types, function ($carry, $current) use ($annotation) {
            return $carry || ($annotation instanceof $current);
        }, false);
    }

    protected function filterNonSupportedAnnotations($annotations, $types)
    {
        return array_filter($annotations, function ($annotation) use ($types) {
            return $this->isAnnotationSupported($annotation, $types);
        });
    }
}
