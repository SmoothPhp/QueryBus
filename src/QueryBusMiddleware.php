<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus;

/**
 * Interface QueryBusMiddleware
 * @package SmoothPhp\QueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
interface QueryBusMiddleware
{
    /**
     * @param mixed $query
     * @param callable $next
     * @return mixed
     */
    public function query($query, callable $next);
}
