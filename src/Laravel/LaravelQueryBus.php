<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus\Laravel;

use SmoothPhp\QueryBus\QueryBus;
use SmoothPhp\QueryBus\QueryBusMiddleware;

/**
 * Class LaravelQueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
final class LaravelQueryBus implements QueryBus
{
    /** @var QueryBusMiddleware[] */
    private $middlewareChain;

    /**
     * LaravelQueryBus constructor.
     * @param QueryBusMiddleware[] $queryBusMiddleware
     */
    public function __construct(QueryBusMiddleware ...$queryBusMiddleware)
    {
        $this->middlewareChain = $this->generateMiddlewareCallChain($queryBusMiddleware);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function query($query)
    {
        return ($this->middlewareChain)($query);
    }

    /**
     * @param $middlewareList
     * @return callable
     */
    private function generateMiddlewareCallChain($middlewareList)
    {
        $lastCallable = function () {
            // the final callable does not run
        };

        while ($middleware = array_pop($middlewareList)) {
            $lastCallable = function ($command) use ($middleware, $lastCallable) {
                return $middleware->query($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}