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
    public function boot()
    {
        $configPath = __DIR__ . '/../../config/querybus.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        foreach ($this->app['config']->get('querybus.query_bus_middleware') as $middleware) {
            $this->app->make(QueryBus::class)->addToMiddlewareChain($this->app->make($middleware));
        }
    }
    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('querybus.php');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../config/querybus.php';
        $this->mergeConfigFrom($configPath, 'querybus');

        $this->app->bind(QueryTranslator::class, $this->app['config']->get('querybus.query_bus_translator'));

        $middlewareChain = [];


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