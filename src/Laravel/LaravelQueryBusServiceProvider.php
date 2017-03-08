<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus\Laravel;

use Illuminate\Support\ServiceProvider;
use SmoothPhp\QueryBus\QueryBus;
use SmoothPhp\QueryBus\QueryTranslator;

/**
 * Class LaravelQueryBusServiceProvider
 * @package SmoothPhp\QueryBus\Laravel
 * @author Simon Bennett <simon@bennett.im>
 */
final class LaravelQueryBusServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(QueryTranslator::class, $this->app['config']->get('query_bus_translator'));

        $middlewareChain = [];

        foreach ($this->app['config']->get('querybus.query_bus_middleware') as $middleware) {
            $this->app->singleton($middleware);
            $middlewareChain[] = $this->app->make($middleware);
        }
        $middlewareChain[] = new LaravelQueryHandlerMiddleware(
            $this->app, $this->app->make(QueryTranslator::class)
        );

        $this->app->singleton(
            QueryBus::class,
            function () use ($middlewareChain) {
                return new LaravelQueryBus(...$middlewareChain);
            }
        );
    }
}