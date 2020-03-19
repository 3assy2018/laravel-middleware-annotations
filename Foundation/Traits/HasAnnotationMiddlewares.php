<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 3:10 AM
 */

namespace M3assy\LaravelAnnotations\Foundation\Traits;

use M3assy\LaravelAnnotations\Facades\Annotation;

trait HasAnnotationMiddlewares
{
    public function __construct()
    {
        Annotation::read($this);
    }
}
