<?php

namespace M3assy\LaravelAnnotations\Annotations;
use M3assy\LaravelAnnotations\Foundation\Types\MiddlewareAnnotation;
/**
 * @Annotation
 */
class Role extends MiddlewareAnnotation
{
    public function validateGivenValue()
    {
        $model = config('laratrust.models.role');
        $values = explode('|', $this->getValue());
        $values = array_filter($values);
        return empty($values) ? false :  count($values) == $model::whereIn('name', $values)->count();
    }

    public function getDifference()
    {
        $model = config('laratrust.models.role');
        $values = explode("|", $this->getValue());
        $dbValues = $model::whereIn('name', $values)->get()->pluck("name")->toArray();
        return array_diff($values, $dbValues);
    }
}
