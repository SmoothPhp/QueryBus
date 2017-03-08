<?php declare (strict_types=1);

namespace SmoothPhp\QueryBus\Test\QueryBus;

use Illuminate\Contracts\Foundation\Application;
use SmoothPhp\QueryBus\Laravel\LaravelQueryBus;
use SmoothPhp\QueryBus\Laravel\LaravelQueryHandlerMiddleware;
use SmoothPhp\QueryBus\QueryBusMiddleware;
use SmoothPhp\QueryBus\SimpleQueryTranslator;
use SmoothPhp\QueryBus\Test\TestCase;

/**
 * Class QueryBusTest
 * @package SmoothPhp\QueryBus\Test\QueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
final class QueryBusTest extends TestCase
{
    /**
     * @test
     */
    public function laravel_query_bus_test()
    {
        if (interface_exists(Application::class)) {
            $container = $this->getMock(Application::class);
            $container
                ->expects($this->once())
                ->method('make')
                ->with(QueryHandler::class)
                ->willReturn(new QueryHandler);

            $translator = new SimpleQueryTranslator;

            $handlerMiddleware = new LaravelQueryHandlerMiddleware($container, $translator);

            $queryBus = new LaravelQueryBus($handlerMiddleware);

            $result = $queryBus->query(new Query);

            $this->assertTrue($result['result']);
        }
    }
    /**
     * @test
     */
    public function laravel_query_bus_test_middleware()
    {
        if (interface_exists(Application::class)) {
            $container = $this->getMock(Application::class);
            $container
                ->expects($this->once())
                ->method('make')
                ->with(QueryHandler::class)
                ->willReturn(new QueryHandler);

            $translator = new SimpleQueryTranslator;

            $handlerMiddleware = new LaravelQueryHandlerMiddleware($container, $translator);

            $queryBus = new LaravelQueryBus(new class implements QueryBusMiddleware {

                /**
                 * @param mixed $query
                 * @param callable $next
                 * @return mixed
                 */
                public function query($query, callable $next)
                {
                    return array_merge($next($query),['middleware' => true]);
                }
            },$handlerMiddleware);

            $result = $queryBus->query(new Query);

            $this->assertTrue($result['result']);
            $this->assertTrue($result['middleware']);

        }
    }
}
