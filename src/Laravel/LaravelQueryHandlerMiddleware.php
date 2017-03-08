<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus\Laravel;

use Illuminate\Contracts\Foundation\Application;
use SmoothPhp\QueryBus\QueryBusMiddleware;
use SmoothPhp\QueryBus\QueryTranslator;

/**
 * Class LaravelQueryHandlerMiddleware
 * @package SmoothPhp\QueryBus\Laravel
 * @author Simon Bennett <simon@bennett.im>
 */
final class LaravelQueryHandlerMiddleware implements QueryBusMiddleware
{
    /**
     * @var Application
     */
    private $application;
    /**
     * @var QueryTranslator
     */
    private $queryTranslator;

    /**
     * @param Application $application
     * @param QueryTranslator $queryTranslator
     */
    public function __construct(Application $application, QueryTranslator $queryTranslator)
    {
        $this->application = $application;
        $this->queryTranslator = $queryTranslator;
    }

    /**
     * @param mixed $query
     * @param callable $next
     * @return \stdClass
     */
    public function query($query, callable $next)
    {
        $handler = $this->application->make($this->queryTranslator->toQueryHandler($query));

        if (is_callable($handler)) {
            return $handler($query);
        }

        return $handler->handle($query);
    }
}
