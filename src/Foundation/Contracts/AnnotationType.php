<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 12:25 AM
 */

namespace M3assy\LaravelAnnotations\Foundation\Contracts;


interface AnnotationType
{
    public function formResponse();

    public function getName();

    public function getValue();

    public function validateGivenValue();

    public function getResponse();

    public function getInvalidInputMessage();

}
