<?php declare (strict_types=1);
namespace SmoothPhp\QueryBus;

/**
 * Class SimpleQueryTranslator
 * @package SmoothPhp\QueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
final class SimpleQueryTranslator implements QueryTranslator
{
    /**
     * @param mixed $query
     * @return string
     */
    public function toQueryHandler($query)
    {
        return get_class($query) . 'Handler';
    }
}