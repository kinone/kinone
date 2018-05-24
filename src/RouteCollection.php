<?php
/**
 * Description of RouteCollection.php.
 *
 * @package Kinone\Kinone
 */

namespace Kinone\Kinone;

use Kinone\Kinone\Route\RouteInterface;

class RouteCollection
{
    /**
     * @var RouteInterface[]
     */
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function add(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    public function addCollection(self $collection)
    {
        foreach ($collection->all() as $route) {
            $this->add($route);
        }
    }

    /**
     * @param Request $request
     * @return RouteInterface |bool
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
