<?php
/**
 * Description of Dispatcher.php.
 *
 * @package Kinone\Kinone
 */

namespace Kinone\Kinone;

use Kinone\Kinone\Route\CallableRoute;
use Kinone\Kinone\Route\StaticRoute;

class Dispatcher
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->routes = new RouteCollection();

        $this->app = $app;
    }

    public function bind(string $prefix, \ReflectionClass $ins)
    {
        if ($prefix[0] != '/') {
            $prefix = '/' . $prefix;
        }

        $collection = StaticRoute::resolve($prefix, $ins);
        $this->routes->addCollection($collection);
    }

    public function match(string $pattern, callable $handler)
    {
        $route = new CallableRoute($pattern, $handler);
        $this->routes->add($route);

        return $route;
    }

    /**
     * @param Request $request
     * @return callable
     */
    public function dispatch(Request $request)
    {
        $route = $this->routes->match($request);
        if (!$route) {
            throw new Exception('Page Not Found');
        }

        return $route->getHandler();
    }
}
