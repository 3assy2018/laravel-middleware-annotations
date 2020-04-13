<?php

namespace M3assy\LaravelAnnotations;


use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Support\ServiceProvider;
use M3assy\LaravelAnnotations\Console\MakeMiddlewareAnnotationCommand;
use M3assy\LaravelAnnotations\Console\ScanNewAclCommand;
use M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Console\ControllerResolver;
use M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\src\Foundation\ControllerDispatcherWithAnnotationReader;

class LaravelAnnotationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'm3assy');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'm3assy');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        if(class_exists(AnnotationRegistry::class)){
            AnnotationRegistry::registerLoader('class_exists');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/annotations.php', 'laravel_annotations');
        // Register the service the package provides.
        $this->app->singleton('laravel_annotation', function ($app) {
            return new LaravelAnnotation();
        });

        $this->app->bind(ControllerDispatcher::class, ControllerDispatcherWithAnnotationReader::class);

        $this->app->singleton(ControllerResolver::class, function ($app){
            return new ControllerResolver();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel_annotations'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/annotations.php' => config_path('annotations.php'),
        ], 'laravel_annotations.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/m3assy'),
        ], 'laravelannotation.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/m3assy'),
        ], 'laravelannotation.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/m3assy'),
        ], 'laravelannotation.views');*/

        // Registering package commands.
         $this->commands([
             MakeMiddlewareAnnotationCommand::class,
             ScanNewAclCommand::class,
         ]);
    }
}
