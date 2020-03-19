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
        $values = explode('|', $this->value);
        return count($values) == \App\Models\Role::whereIn('name', $values)->count();
    }

    public function getDifference()
    {
        $values = explode("|", $this->value);
        $dbValues = \App\Models\Role::whereIn('name', $values)->get()->pluck("name")->toArray();
        return array_diff($values, $dbValues);
    }
}
