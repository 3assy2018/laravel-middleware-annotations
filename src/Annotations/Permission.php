<?php

namespace M3assy\LaravelAnnotations\Annotations;

use M3assy\LaravelAnnotations\Foundation\Types\MiddlewareAnnotation;
/**
 * @Annotation
 */
class Permission extends MiddlewareAnnotation
{
    public function validateGivenValue()
    {
        $values = explode('|', $this->value);
        return \App\Models\Permission::whereIn('name', $values)->count() == count($values);
    }

    public function getDifference()
    {
        $values = explode("|", $this->value);
        $dbValues = \App\Models\Permission::whereIn('name', $values)->get()->pluck("name")->toArray();
        return array_diff($values, $dbValues);
    }
}
