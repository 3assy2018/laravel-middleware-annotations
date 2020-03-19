<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 9:31 AM
 */

namespace M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\Foundation\Pipes;


use Closure;
use M3assy\LaravelAnnotations\Foundation\AnnotationScanner;

class ACLCreator
{
    public function handle(AnnotationScanner $annotationScanner, Closure $next)
    {
        $annotationScanner->results = array_unique($annotationScanner->results);
        foreach ($annotationScanner->results as $aclModelKey => $newValues){
            $model = config("laratrust.models.$aclModelKey");
            $instances = array_map(function ($newValue) use ($model) {
                $humanized = ucwords(str_replace('-', ' ', $newValue));
                return $model::create(
                    [
                        'name' => $newValue,
                        'display_name' => $humanized,
                        'description' => $humanized,
                    ]
                );
            },$newValues);
            $annotationScanner->results[$aclModelKey] = $instances;
        }
        return $next($annotationScanner);
    }
}
