<?php

namespace M3assy\LaravelAnnotations\Foundation\Types;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;
use M3assy\LaravelAnnotations\Foundation\Contracts\AnnotationType;

class MiddlewareAnnotation implements AnnotationType
{

    protected $middlewareName, $value, $response;
    public const PARAM_NOTATION = ":";
    protected $invalidInputMessage = "Invalid Parameters";
    protected $case = "camel";

    public function __construct($param)
    {
        $this->middlewareName = $this->getName();
        $this->value = $param['value'] ?? null;
        $this->response = $this->formResponse();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function formResponse()
    {
        return $this->validateGivenValue()
            ? ($this->value ? $this->middlewareName.static::PARAM_NOTATION.$this->value : $this->middlewareName)
            : null;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getName(): string
    {
        return $this->middlewareName
            ?? Str::{$this->case}((new ReflectionClass(static::class))->getShortName());
    }

    /**
     * @return bool
     */
    public function validateGivenValue()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getInvalidInputMessage(): string
    {
        return $this->invalidInputMessage;
    }

    public function getValue()
    {
        return $this->value;
    }
}
