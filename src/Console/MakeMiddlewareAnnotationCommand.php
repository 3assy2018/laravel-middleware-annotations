<?php

namespace M3assy\LaravelAnnotations\Console;

use Illuminate\Console\GeneratorCommand;

class MakeMiddlewareAnnotationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:annotation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Annotation Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Annotation Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__."/stubs/annotation.stub";
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config("annotations.namespace")[1];
    }
}
