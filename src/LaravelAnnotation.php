<?php

namespace M3assy\LaravelAnnotations;

use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Illuminate\Routing\Controller;
use Illuminate\Pipeline\Pipeline;
use M3assy\LaravelAnnotations\Foundation\Pipes\{ClassMiddlewareResolver, MethodMiddlewareResolver};

class LaravelAnnotation extends SimpleAnnotationReader
{
    protected $route;
    private $pipes = [
        ClassMiddlewareResolver::class,
        MethodMiddlewareResolver::class,
    ];
    /**
     * LaravelAnnotations constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->importNamespaces();
    }

    /**
     * @param Controller $controller
     */
    public function read(Controller $controller)
    {
        if(request()->route())
            app(Pipeline::class)
                ->send($controller)
                ->through($this->pipes)
                ->then(function ($cn){ return $cn; });
    }


    private function importNamespaces()
    {
        foreach (config("annotations.namespace") as $namespace){
            $this->addNamespace($namespace);
        }
    }
}
