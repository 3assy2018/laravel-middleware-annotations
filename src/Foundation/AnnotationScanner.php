<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 3/18/2020
 * Time: 6:58 AM
 */

namespace M3assy\LaravelAnnotations\Foundation;


use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Route;

class AnnotationScanner
{
    private $pipes = [];
    protected $scanFor;
    public  $scans;
    public  $results = [];

    public function __construct()
    {
        $this->scans = $this->getRegisteredActions();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getRegisteredActions()
    {
        return collect(Route::getRoutes()->get())
                    ->map(function ($route){
                        return $route->getAction("controller");
                    })
                    ->filter()
                    ->map(function ($route){
                        return (new ControllerReflector())->setRouteQuery($route);
                    });
    }


    /**
     * @param array $scanFor
     * @return $this
     */
    public function setScanFor(array $scanFor)
    {
        $this->scanFor = $scanFor;
        return $this;
    }

    /**
     * @return $this
     */
    public function scan()
    {
        app(Pipeline::class)
            ->send($this)
            ->through(array_map(function ($pipe){
                return $pipe.":".implode(',', $this->scanFor);
            },$this->pipes))
            ->then(function ($scanner){
                return $scanner;
            });
        return $this;
    }

    /**
     * @param mixed ...$pipe
     * @return $this
     */
    public function addPipe(...$pipe)
    {
        $this->pipes = array_merge($this->pipes, $pipe);
        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

}
