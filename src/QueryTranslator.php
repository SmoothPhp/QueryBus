<?php
namespace SmoothPhp\QueryBus;

/**
 * Interface QueryTranslator
 * @package SmoothPhp\QueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
interface QueryTranslator
{
    /**
     * @param mixed $query
     * @return string
     */
    public function toQueryHandler($query);
}