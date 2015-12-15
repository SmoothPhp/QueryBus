<?php
namespace SmoothPhp\QueryBus;

/**
 * Interface QueryBus
 * @package SmoothPhp\QueryBus
 * @author Simon Bennett <simon@bennett.im>
 */
interface QueryBus
{
    /**
     * @param $query
     * @return \stdClass|array
     */
    public function query($query);
}