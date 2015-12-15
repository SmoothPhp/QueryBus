<?php
namespace SmoothPhp\QueryBus\Laravel;

use SmoothPhp\QueryBus\QueryBus;
use SmoothPhp\QueryBus\QueryTranslator;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class LaravelQueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
final class LaravelQueryBus implements QueryBus
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
     * @param $query
     * @param null $decorator
     * @return \stdClass
     */
    public function query($query, $decorator = null)
    {
        return $this->application->make($this->queryTranslator->toQueryHandler($query))->handle($query);
    }
}