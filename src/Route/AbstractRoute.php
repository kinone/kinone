<?php
/**
 * Description of AbstractRoute.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

abstract class AbstractRoute implements RouteInterface
{
    const TYPE_STATIC = 1;
    const TYPE_REGEX = 2;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var string[]
     */
    protected $methods;

    public function __construct(string $path, callable $handler)
    {
        $this->path = $path;
        $this->handler = $handler;
    }

    public function getHandler(): callable
    {
        return $this->handler;
    }

    public function method(string $method)
    {
        $this->methods = explode('|', $method);
    }
}
