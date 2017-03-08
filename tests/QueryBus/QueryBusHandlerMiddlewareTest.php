<?php declare(strict_types = 1);

namespace SmoothPhp\QueryBus\Test\QueryBus;

use Illuminate\Contracts\Foundation\Application;
use SmoothPhp\QueryBus\Laravel\LaravelQueryBus;
use SmoothPhp\QueryBus\Laravel\LaravelQueryHandlerMiddleware;
use SmoothPhp\QueryBus\SimpleQueryTranslator;
use SmoothPhp\QueryBus\Test\TestCase;

/**
 * Class QueryBusTest
 * @package SmoothPhp\QueryBus\Test
 * @author jrdn hannah <jordan@hotsnapper.com>
 */
final class QueryBusHandlerMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function test_handling_queries()
    {
        if (interface_exists(Application::class)) {
            $container = $this->getMock(Application::class);
            $container
                ->expects($this->once())
                ->method('make')
                ->with(QueryHandler::class)
                ->willReturn(new QueryHandler);

            $translator = new SimpleQueryTranslator;

            $bus = new LaravelQueryHandlerMiddleware($container, $translator);

            $result = $bus->query(new Query,function(){});

            $this->assertTrue($result['result']);
        }
    }

    /**
     * @test
     */
    public function test_handling_callable_queries()
    {
        if (interface_exists(Application::class)) {
            $container = $this->getMock(Application::class);
            $container
                ->expects($this->once())
                ->method('make')
                ->with(CallableQueryHandler::class)
                ->willReturn(new CallableQueryHandler);

            $translator = new SimpleQueryTranslator;

            $bus = new LaravelQueryHandlerMiddleware($container, $translator);

            $result = $bus->query(new CallableQuery,function(){});

            $this->assertTrue($result['result']);
        }
    }
}

final class Query {

}

final class CallableQuery {

}

final class QueryHandler {
    public function handle(Query $query) {
        return ['result' => true];
    }
}

final class CallableQueryHandler {
    public function __invoke(CallableQuery $query) {
        return ['result' => true];
    }
}