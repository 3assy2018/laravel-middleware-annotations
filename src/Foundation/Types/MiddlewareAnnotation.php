<?php

namespace M3assy\LaravelAnnotations\Foundation\Types;

use Closure;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use M3assy\LaravelAnnotations\Console\ControllerResolver;
use ReflectionClass;
use M3assy\LaravelAnnotations\Foundation\Contracts\AnnotationType;

class MiddlewareAnnotation implements AnnotationType
{

    protected $middlewareName, $value, $response, $options = [];
    public const PARAM_NOTATION = ":";
    protected $invalidInputMessage = "Invalid Parameters";
    protected $case = "camel";

    public function __construct($param)
    {
        $this->middlewareName = $this->getName();
        $this->value = $param['value'] ?? null;
        $this->response = $this->formResponse();
        $options = Arr::only($param, ['except', 'only']);
        foreach ($options as $key => $value){
            $options[$key] = explode(',', $value);
        }
        $this->options = $options;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function formResponse()
    {
        return $this->validateGivenValue()
            ? ($this->value
                ? $this->middlewareName.static::PARAM_NOTATION.$this->getValue()
                : $this->middlewareName)
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
        $originalValue = $this->value;
        $valueBindingClosure = Closure::bind(function () use ($originalValue){
            return eval("return \"$originalValue\";");
        }, app(ControllerResolver::class)->getController());
        return $valueBindingClosure();
    }

    public function getOptions()
    {
        return $this->options;
    }
}
