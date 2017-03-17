<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus\Laravel;

use Dizinga\Infrastructure\QueryBusCache\QueryBusCacheMiddleware;
use SmoothPhp\QueryBus\QueryBus;
use SmoothPhp\QueryBus\QueryBusMiddleware;

/**
 * Class LaravelQueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
final class LaravelQueryBus implements QueryBus
{
    /** @var QueryBusMiddleware[] */
    private $queryBusMiddleware;

    /**
     * LaravelQueryBus constructor.
     * @param QueryBusMiddleware[] $queryBusMiddleware
     */
    public function __construct(QueryBusMiddleware ...$queryBusMiddleware)
    {
        $this->queryBusMiddleware = $queryBusMiddleware;
    }

    /**
     * @param QueryBusMiddleware $middleware
     */
    public function addToMiddlewareChain(QueryBusMiddleware $middleware)
    {
        array_unshift($this->queryBusMiddleware, $middleware);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function query($query)
    {
        return $this->generateMiddlewareCallChain()($query);
    }

    /**
     * @return callable
     */
    private function generateMiddlewareCallChain()
    {
        $middlewareChain = $this->queryBusMiddleware;
        $lastCallable = function () {
            // the final callable does not run
        };

        while ($middleware = array_pop($middlewareChain)) {
            $lastCallable = function ($command) use ($middleware, $lastCallable) {
                return $middleware->query($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}