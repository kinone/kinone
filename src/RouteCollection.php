<?php
/**
 * Description of RouteCollection.php.
 *
 * @package Kinone\Kinone
 */

namespace Kinone\Kinone;

use Kinone\Kinone\Route\RouteInterface;

final class RouteCollection
{
    /**
     * @var RouteInterface[]
     */
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * @param RouteInterface $route
     */
    public function add(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    /**
     * @param self $collection
     * @return RouteCollection
     */
    public function addCollection(RouteCollection $collection)
    {
        foreach ($collection->all() as $route) {
            $this->add($route);
        }

        return $this;
    }

    /**
     * @param Request $request
     * @return RouteInterface|bool
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                return $route;
            }
        }

        return false;
    }

    /**
     * @return RouteInterface[]
     */
    public function all()
    {
        return $this->routes;
    }
}
