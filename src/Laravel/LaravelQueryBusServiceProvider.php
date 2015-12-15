<?php
namespace SmoothPhp\QueryBus\Laravel;

use Illuminate\Support\ServiceProvider;
use SmoothPhp\QueryBus\QueryBus;
use SmoothPhp\QueryBus\QueryTranslator;
use SmoothPhp\QueryBus\SimpleQueryTranslator;

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
        $this->app->bind(QueryTranslator::class, SimpleQueryTranslator::class);
        $this->app->bind(QueryBus::class, LaravelQueryBus::class);
    }
}