<?php declare(strict_types=1);

namespace SmoothPhp\QueryBus\Test;

/**
 * Class TestCase
 * @package SmoothPhp\QueryBus\Test
 * @author jrdn hannah <jordan@hotsnapper.com>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        require_once __DIR__ . '/../vendor/autoload.php';
    }
}